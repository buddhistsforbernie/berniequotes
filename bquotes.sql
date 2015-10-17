-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 17, 2015 at 05:46 AM
-- Server version: 5.5.44-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bquotes`
--

-- --------------------------------------------------------

--
-- Table structure for table `banned`
--

CREATE TABLE IF NOT EXISTS `banned` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE IF NOT EXISTS `quotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote` varchar(2000) NOT NULL,
  `source` varchar(255) NOT NULL,
  `sourcename` varchar(255) DEFAULT NULL,
  `verified` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `quotes`
--

INSERT INTO `quotes` (`id`, `quote`, `source`, `sourcename`, `verified`) VALUES
(2, '[I]f you are serious about real healthcare reform, the only way to go is single-payer. ', 'http://www.thenation.com/video/sanders-schools-mccain-public-healthcare', '"Sanders Schools McCain on Public Healthcare"', 1),
(3, 'Enough is enough! [...] How many homes can you own?', 'http://www.salon.com/2011/10/25/bernie_sanders_war_on_the_banks/', '"Sen. Bernie Sanders ends filibuster"', 1),
(4, 'The bottom line is when Senator Inhofe says global warming is a hoax, he is just dead wrong, according to the vast majority of climate scientists', 'http://www.huffingtonpost.com/2012/07/31/bernie-sanders-climate-change_n_1723334.html', 'Senator Bernie Sanders: Climate Change Is Real, Senator Inhofe Is ''Dead Wrong''', 1),
(5, 'The real issue here, if you look at the Koch Brothers'' agenda, is: look at what many of the extreme right-wing people believe. Obamacare is just the tip of the iceberg. These people want to abolish the concept of the minimum wage, they want to privatize the Veteran''s Administration, they want to privatize Social Security, end Medicare as we know it, massive cuts in Medicaid, wipe out the EPA, you don’t have an Environmental Protection Agency anymore, Department of Energy gone, Department of Education gone. That is the agenda. And many people don’t understand that the Koch Brothers have poured hundreds and hundreds of millions of dollars into the tea party and two other kinds of ancillary organizations to push this agenda.\r\n', 'http://www.youtube.com/watch?v=6LC_4h8rk9E', 'MSNBC News Interview (October 7, 2013)', 1),
(6, 'I find it remarkable that Saudi Arabia, which borders Iraq and is controlled by a multi-billion dollar family, is demanding that U.S. combat troops have ‘boots on the ground’ against ISIS. Where are the Saudi troops? With the third largest military budget in the world and an army far larger than ISIS, the Saudi government must accept its full responsibility for stability in their own region of the world. Ultimately, this is a profound struggle for the soul of Islam, and the anti-ISIS Muslim nations must lead that fight. While the United States and other western nations should be supportive, the Muslim nations must lead.\r\n', 'http://www.newsmax.com/Newsfront/Bernie-Sanders-ISIS-US-troops/2015/03/06/id/628788/', 'Sen. Bernie Sanders Rips Saudis for Demanding US Troops Fight ISIS', 1),
(7, 'What we have seen is that while the average person is working longer hours for lower wages, we have seen a huge increase in income and wealth inequality, which is now reaching obscene levels. This is a rigged economy, which works for the rich and the powerful, and is not working for ordinary Americans … You know, this country just does not belong to a handful of billionaires.', 'http://www.theguardian.com/us-news/2015/apr/30/bernie-sanders-confirms-presidential-run-and-damns-americas-inequities', 'Bernie Sanders confirms presidential run and damns America''s inequities', 1),
(8, 'No single financial institution should have holdings so extensive that its failure could send the world economy into another financial crisis ... If an institution is too big to fail, it is too big to exist.', 'http://chicago.suntimes.com/politics/7/71/569095/sanders-socialist-sure-gets-right-big-banks', 'Steve Huntley: Sanders the socialist sure gets it right on big banks', 1),
(9, 'Are we happy that 99% of all new income is going to the top 1%? Are we happy that one family in this country owns more than the bottom 130 million people? ', 'http://www.youtube.com/watch?v=BFAq-4Vv5c0', 'Late Night with Seth Meyers, June 2, 2015', 1),
(10, 'Warren Buffett, one of the richest guys in the world, openly admits that his effective tax rate is lower than his secretary''s. It''s time to tell the billionaire class that if they want to enjoy the benefits of America, they have to accept their responsibilities, and they have to start paying their fair share of taxes.\r\n\r\nTest.', 'http://www.youtube.com/watch?v=BFAq-4Vv5c0', 'Late Night with Seth Meyers, June 2, 2015', 1),
(11, 'In the last thirty years, there has been a massive redistribution of wealth. Unfortunately, it''s gone in the wrong direction. ... All that money that has gone from the middle class to the top 1%, I think it should start coming back to the people who need it the most. ', 'http://www.youtube.com/watch?v=BFAq-4Vv5c0', 'Late Night with Seth Meyers, June 2, 2015', 1),
(12, 'There are millions and millions of people who are tired of establishment politics, who are tired of corporate greed, who want a candidate that will help lead a mass movement in this country. ... What people are saying is, "Enough is enough. The billionaire class cannot have it all."', 'http://www.youtube.com/watch?v=XpgJYNaIeqo', 'Interview with Katie Couric, June 2, 2015', 1),
(13, 'In my view, and we''ve introduced legislation to deal with this, if a bank is too big to fail, it is too big to exist.', 'https://www.youtube.com/watch?v=OewBDIwy-O4', '2016 Presidential Campaign Rally in Madison, Wisconsin, July 1, 2015', 1),
(14, 'A nation that in many ways was created—I''m sorry to have to say this, from way back—on racist principles, that''s a fact, we have come a long way as a nation.', 'http://www.theblaze.com/stories/2015/09/14/bernie-sanders-to-liberty-university-america-founded-on-racist-principles-thats-a-fact/', 'Bernie Sanders to Liberty University: America Founded on ''Racist Principles, That''s a Fact''', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quote_tags`
--

CREATE TABLE IF NOT EXISTS `quote_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

--
-- Dumping data for table `quote_tags`
--

INSERT INTO `quote_tags` (`id`, `qid`, `tid`) VALUES
(1, 2, 2),
(2, 3, 3),
(3, 4, 4),
(10, 6, 11),
(11, 6, 12),
(12, 6, 13),
(16, 8, 17),
(17, 8, 18),
(31, 13, 17),
(32, 13, 33),
(33, 14, 34),
(36, 12, 28),
(37, 10, 23),
(53, 7, 14),
(54, 9, 14),
(55, 11, 14),
(56, 5, 3),
(57, 5, 57),
(58, 5, 58),
(59, 5, 59),
(60, 5, 8),
(61, 5, 61),
(62, 5, 62),
(63, 5, 63);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(17, 'banks'),
(7, 'capitalism'),
(4, 'climate'),
(5, 'corruption'),
(18, 'economy'),
(28, 'establishment'),
(6, 'greed'),
(2, 'healthcare'),
(19, 'income'),
(14, 'inequality'),
(57, 'Koch'),
(33, 'legislation'),
(63, 'Medicare'),
(13, 'middle-east'),
(59, 'minimum-wage'),
(29, 'movement'),
(58, 'Obamacare'),
(8, 'privatization'),
(34, 'racism'),
(12, 'religion'),
(9, 'rich'),
(10, 'right-wing'),
(62, 'Social Security'),
(23, 'tax'),
(61, 'Veterans'),
(16, 'wages'),
(11, 'war'),
(3, 'wealth');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
