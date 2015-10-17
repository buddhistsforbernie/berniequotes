$( document ).ready(function() {
	$( ".form input" ).bind('keypress', function(e){
		if ( e.keyCode == 13 ) {
			$( this ).find( 'input[type=button]:first' ).click();
		}
	});

	doAjax(false,"randquote");
	clearForm();
});

var G_tags = [];

function doAjax(submit,formid) {

	// variable to hold request
	var request;
	// abort any pending request
	if (request) {
		request.abort();
	}
	
	var serializedData = '';

	var filtered_tags = [];
	
	if(submit) {
		
		// setup some local variables
		var $form = $("#"+formid);
		// let's select and cache all the fields
		var $inputs = $("#"+formid+" input,#"+formid+" textarea");
		
		if($inputs.length > 0) {
			
			if(!validateForm(formid))
				return;

			// get form name
				
			// serialize the data in the form
			serializedData = $inputs.serialize()+"&form_id="+formid;
		}
		else serializedData = "form_id="+formid;

		// add tags

		if(formid == 'getquotes') {
			var tags_filter = [];
			filtered_tags = new Array();
			$('.tag-active').each(function( index ) {
				filtered_tags[$( this ).text().replace(/‑/g,'-')] = true;
				tags_filter.push($( this ).text().replace(/‑/g,'-'));
			});
			serializedData += '&tag_filter='+tags_filter.join(',');
		}

		// let's disable the inputs for the duration of the ajax request
		// Note: we disable elements AFTER the form data has been serialized.
		// Disabled form elements will not be serialized.
		$inputs.prop("disabled", true);
		
	}
	else serializedData = "form_id="+formid;
	
	// fire off the request to /ajax.php
	request = $.ajax({
		url: "ajax.php",
		type: "post",
		data: serializedData,
		dataType: "json"
	});

	// callback handler that will be called on success
	request.done(function (response, textStatus, jqXHR){
		// log a message to the console
	});

	// callback handler that will be called on failure
	request.fail(function (jqXHR, textStatus, errorThrown){
		// log the error to the console
		console.error(
			"The following error occured: "+
			textStatus, errorThrown
		);
	});

	// callback handler that will be called on success
	request.success(function (result){
		if(formid == 'addquote' || formid.indexOf('delete-') == 0)
			location.reload();
			
		if(result.tags && result.tags.length != 0) {
			$('#tag-cloud').empty();
			G_tags = new Array();
			
			var tmax = 0; 
			
			for(var i = 0; i < result.tags.length; i++) {
				if(tmax < result.tags[i]['count'])
					tmax = result.tags[i]['count'];
			}
			for(var i = 0; i < result.tags.length; i++) {
				G_tags.push(result.tags[i]['name']);
				var fontSize = (100.0*(1.0+(1.5*result.tags[i]['count']-tmax/2)/tmax))+"%";
				$('#tag-cloud').append('<span class="tag-'+(filtered_tags[result.tags[i]['name']]?'':'in')+'active" title="'+result.tags[i]['count']+' quote'+(result.tags[i]['count']>1?'s':'')+'" style="font-size:'+fontSize+' !important" onclick="toggleTag(this)">'+result.tags[i]['name'].replace(/-/g,'‑')+'</span> ');
			}
		}

		if(result.total && result.total.length != 0) {
			$('#total-no').html(result.total);
		}

		if(result.quotes) {
			$('#quote-title').html(formid == 'randquote' ? 'Random Quote:' : 'Quote List:');
				
			$('#quote-list').empty();
			
			if(result.quotes.length == 0)
				$('#quote-list').append('<div class="quote">Sorry, no quotes found!</div>');
			
			var j = 0;
			
			for (quote of result.quotes) {
				$('#quote-list').append('<div class="quote'+(quote['verified']==0?'-unverified':'')+'"'+(isAdmin?' id="quote-'+j+'" onclick="editQuote(this,'+j+')"':'')+'><div class="quote-text">'+quote['quote'].replace(/\n/g,'<br/>')+'</div><div class="quote-source"><a target="_blank" href="'+quote['source']+'">'+(quote['sourcename']?quote['sourcename']:quote['source'])+'</a></div><div class="quote-tags"><span class="tag-quote">'+quote['tag'].replace(/-/g,'‑').replace(/,/g,'</span><span class="tag-quote">')+'</span></div><div class="qm">❝</div></div>');
				if(isAdmin)
					$('#quote-list').append('<div class="quote quote-admin form" id="quote-admin-'+j+'"><div class="p">Quote: <br/><textarea id="quote" name="quote" cols=64 rows="8" cols="40"  maxlength="2000">'+quote['quote']+'</textarea></div><div class="p">Source Title (opt.): <input id="sourcename" name="sourcename" value="'+(quote['sourcename']?quote['sourcename']:quote['source'])+'"></div><div class="p">Source URL (req.): <input id="source" name="source" value="'+quote['source']+'"></div><div class="p">Tags (comma-separated, req.): <input id="tags" name="tags" value="'+quote['tag']+'"></div><div class="p">Verified <input type="checkbox" id="verified" name="verified"'+(quote['verified'] == 1?' checked':'')+'></div><div class="p"><input type="button" value="save" onclick="doAjax(true,\'quote-admin-'+j+'\')"><input type="button" value="delete" onclick="doAjax(false,\'delete-'+quote['id']+'\')"></div><input type="hidden" name="qid" value="'+quote['id']+'"></div>');
					
				j++;
			}
		}

	});

	// callback handler that will be called regardless
	// if the request failed or succeeded
	request.always(function () {
		// reenable the inputs
		if($inputs)
			$inputs.prop("disabled", false);
	});
}


function validateForm(id) {
	var error = '';
	
	if(id == 'addquote') {
		var quote = $('#quote').val();
		var sourcename = $('#sourcename').val();
		var source = $('#source').val();
		var tags = $('#tags').val();
		
		if(quote.length > 2000) {
			error = 'The quote must be less than 1000 characters. Your quote is '+quote.length+' characters long.';
		}
		else if(quote.length < 1) {
			error = 'The quote text cannot be empty.';
		}

		if(source.length > 255) {
			error = 'The source URL is too long.';
		}
		else if(source.length < 1) {
			error = 'The source URL cannot be empty.';
		}

		if(sourcename.length > 255) {
			error = 'The source title is too long.';
		}

		if(tags.length > 255) {
			error = 'The tag list is too long.';
		}
	}
	if(error.length > 0) {
		alert(error);
		return false;
	}
	
	return true;
}


function toggleTag(el) {
	$(el).attr('class',$(el).attr('class') == 'tag-inactive'?'tag-active':'tag-inactive');
}

function clearForm() {
	$('#quote').val('');
	$('#sourcename').val('');
	$('#source').val('');
	$('#tags').val('');
		
}

function editQuote(el,which) {
	$(el).hide();
	$('#quote-admin-'+which).show();
}
