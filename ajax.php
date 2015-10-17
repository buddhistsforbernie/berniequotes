<?php

require_once('config.php');

error_log('called');

error_log($isAdmin?'admin':'not admin');

function getTags() {
	
	global $con,$isAdmin;
	
	$lista = [];
	$sql="SELECT name, tags.id FROM tags,quote_tags,quotes WHERE tags.id=quote_tags.tid AND quotes.id=quote_tags.qid".($isAdmin?"":" AND quotes.verified=1")." ORDER BY name";
	
	$query = mysqli_query($con, $sql) or trigger_error("Query Failed: " . mysqli_error($con)); 
	
	$lastname = '';
	
	while($row = mysqli_fetch_assoc($query)) {
		if($row['name'] == $lastname && $lastname != '') {
			$lista[count($lista)-1]['count']++;
		}
		else {
			$row['count'] = 1;
			$lista[] = $row;
			$lastname = $row['name'];
		}
	}
	
	return $lista;
}

function getQuoteNumber() {
	global $con, $isAdmin;

	$sql="SELECT id FROM quotes WHERE ".($isAdmin ? '' : "quotes.verified=")."1";
	$query = mysqli_query($con, $sql) or trigger_error("Query Failed: " . mysqli_error($con)); 
	return mysqli_num_rows($query);
}

function getQuotes() {
	
	global $con, $isAdmin;

	$verified = $isAdmin ? '' : " AND quotes.verified=1";

	$tags = '';
	
	// get tag filters
	if(isset($_POST['tag_filter']) && preg_match('/^[-A-Za-z0-9_ ,]+$/',$_POST['tag_filter']) === 1) {
		
		$tagsta = explode(',',$_POST['tag_filter']);
		$tagsa = [];
		
		foreach($tagsta as $tag) {
			$tag = mysqli_real_escape_string($con,$tag);
			$tagsa[] = $tag;
		}
		$tags = " AND tags.name IN ('".implode('\',\'',$tagsa)."')";
	}

	$query = '';

	// get search term
	if(isset($_POST['query']) && strlen($_POST['query']) > 2 && strlen($_POST['query']) < 255) {
		
		if(substr($_POST['query'],0,1) == '"' && substr($_POST['query'],-1,1) == '"')		
			$query = " AND quotes.quote LIKE '%".substr($_POST['query'],1,-1)."%'";
		else {
			$terms = explode(" ",$_POST['query']);
			foreach($terms as $term) {
				$query .= " AND quotes.quote LIKE '%".$term."%'";
			}
		}
	}
	
	$order = $_POST['form_id'] == 'randquote' ? " ORDER BY RAND() LIMIT 1" :" ORDER BY quotes.quote";
	
	$sql="SELECT quotes.id AS id, quotes.quote AS quote, quotes.source AS source, quotes.sourcename AS sourcename, quotes.verified AS verified FROM quotes, quote_tags, tags WHERE quotes.id=quote_tags.qid AND quote_tags.tid=tags.id".$verified.$query.$tags.$order;
	
	$query = mysqli_query($con, $sql) or trigger_error("Query Failed: " . mysqli_error($con)); 

	error_log(mysqli_num_rows($query));

	$lista = [];

	// add tags

	while($row = mysqli_fetch_assoc($query)) {
			
		// tag fudge
		
		$sql="SELECT tags.name AS tag FROM quotes, quote_tags, tags WHERE quotes.id=quote_tags.qid AND quote_tags.tid=tags.id AND quotes.id=".$row['id'];

		$querya = mysqli_query($con, $sql) or trigger_error("Query Failed: " . mysqli_error($con)); 
		
		$row['tag'] = '';
		
		while($rowa = mysqli_fetch_assoc($querya)) {
			$row['tag'] .= (strlen($row['tag']) >0?',':''). $rowa['tag'];
		}
		
		error_log($row['tag']);
		
		$lista[] = $row;
	}
	
	return $lista;
}

function insertTags($qid,$tags) {
	
	global $con;
	
	// insert tags

	$tagsta = explode(',',$tags);
	
	$tagsa = [];
	
	foreach($tagsta as $tag) {
		$tag = mysqli_real_escape_string($con,$tag);
		$tagsa[] = $tag;
	}
	
	foreach($tagsa as $tag) {

		// insert new tag
					
		$sql="INSERT IGNORE INTO tags (name) VALUES('".$tag."')";

		$query = mysqli_query($con, $sql) or trigger_error("Query Failed: " . mysqli_error($con));

		// get (new) tag id

		$sql="SELECT id FROM tags WHERE name='".$tag."'";

		$query = mysqli_query($con, $sql) or trigger_error("Query Failed: " . mysqli_error($con));
		
		$tid = mysqli_fetch_assoc($query)['id'];
		
		// insert tags
		
		$sql="INSERT INTO quote_tags (qid,tid) VALUES('".$qid."','".$tid."')";

		$query = mysqli_query($con, $sql) or trigger_error("Query Failed: " . mysqli_error($con));
	}

}

$data = [];

// submissions

if(isset($_POST['form_id']) && $_POST['form_id'] != "") {

	if($_POST['form_id'] == 'randquote') {
		error_log('randquote called');
		$data['quotes'] = getQuotes();
	}
	else if($_POST['form_id'] == 'addquote') {
		error_log('addquote called');

		require_once "recaptchalib.php";
		// your secret key
		$secret = "6LfW-g4TAAAAAKc1mjqZjw6oBzIOwrjjn9iCOUCZ";

		// check secret key
		$reCaptcha = new ReCaptcha($secret);
		 
		// empty response
		$cresponse = null;
		if ($_POST["g-recaptcha-response"]) {
			$cresponse = $reCaptcha->verifyResponse(
				$_SERVER["REMOTE_ADDR"],
				$_POST["g-recaptcha-response"]
			);
		}


		if(($isAdmin || ($cresponse != null && $cresponse->success)) && !filter_var($_POST['source'], FILTER_VALIDATE_URL) === false && strlen($_POST['quote']) > 0 && strlen($_POST['quote']) < 2000 && strlen($_POST['tags']) > 0 && preg_match('/^[-a-zA-Z0-9_ ,]+$/',$_POST['tags']) === 1) {

			error_log('addquote validated');
	
			$quote = mysqli_real_escape_string($con,$_POST['quote']);
			$source = mysqli_real_escape_string($con,$_POST['source']);
			$sourcename = mysqli_real_escape_string($con,$_POST['sourcename']);

			// insert quote

			$sql="INSERT INTO quotes (quote,source,sourcename) VALUES('".$quote."','".$source."','".$sourcename."')";
			$query = mysqli_query($con, $sql) or trigger_error("Query Failed: " . mysqli_error($con)); 

			// get quote id

			$qid = mysqli_insert_id($con);

			insertTags($qid,$_POST['tags']);

			$data = array(
				'success' => 1,
			);
			die(json_encode($data));
			
		}
		else {
			$data = array(
				'success' => -1,
			);
			die(json_encode($data));
		}
	}
	else if($isAdmin && strpos($_POST['form_id'],'quote-admin-') === 0) {

		error_log($_POST['form_id'].' called');

		$quote = mysqli_real_escape_string($con,$_POST['quote']);
		$source = mysqli_real_escape_string($con,$_POST['source']);
		$sourcename = mysqli_real_escape_string($con,$_POST['sourcename']);
		$qid = (int)mysqli_real_escape_string($con,$_POST['qid']);
		
		error_log($qid.' quote');

		$sql="UPDATE quotes SET quote='".$quote."', source='".$source."', sourcename='".$sourcename."', verified=".(isset($_POST['verified'])?1:0)." WHERE id=".$qid;

		$query = mysqli_query($con, $sql) or trigger_error("Query Failed: " . mysqli_error($con));

		// remove existing tags
		$sql="DELETE FROM quote_tags WHERE qid=".$qid;
		$query = mysqli_query($con, $sql) or trigger_error("Query Failed: " . mysqli_error($con));

		insertTags($qid,$_POST['tags']);


	}
	else if($_POST['form_id'] == 'getquotes') {
		$data['quotes'] = getQuotes();
	}
	$data['tags'] = getTags();
	$data['total'] = getQuoteNumber();
}

$json = json_encode($data);

echo $json;

