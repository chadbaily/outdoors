<?php

   // essential internal stuff -- skip it. Go down to Part One. There
   // are four parts to this file that interest you, all labeled Part
   // One, Two, Three and Four.

   set_magic_quotes_runtime(0);
   error_reporting(E_ALL ^ E_NOTICE);
   if (!ini_get('register_globals')) {
       extract($HTTP_SERVER_VARS);
   }

   if (!function_exists('rcs_id')) {
      function rcs_id($id) { echo "<!-- $id -->\n"; };
   }
   rcs_id('$Id: config.php,v 1.24.2.8 2001/11/09 15:36:03 dairiki Exp $'); 
   define('PHPWIKI_VERSION', '1.2.7');
   // end essential internal stuff


   /////////////////////////////////////////////////////////////////////
   // Part One:
   // Constants and settings. Edit the values below for your site.
   /////////////////////////////////////////////////////////////////////


   // URL of index.php e.g. http://yoursite.com/phpwiki/index.php
   // you can leave this empty - it will be calculated automatically
   //$ScriptUrl = "http://www.outdoorsatuva.org/resources/";
   $ScriptUrl = "http://216.92.238.172/resources/";
   // URL of admin.php e.g. http://yoursite.com/phpwiki/admin.php
   // you can leave this empty - it will be calculated automatically
   // if you fill in $ScriptUrl you *MUST* fill in $AdminUrl as well!
   $AdminUrl = "";

   // Select your language - default language "C": English
   // other languages available: Dutch "nl", Spanish "es", German "de",
   // and Swedish "sv"
   $LANG="C";

   /////////////////////////////////////////////////////////////////////
   // Part Two:
   // Database section
   // set your database here and edit the according section below.
   // For PHP 4.0.4 and later you must use "dba" if you are using 
   // DBM files for storage. "dbm" uses the older deprecated interface.
   // The option 'default' will choose either dbm or dba, depending on
   // the version of PHP you are running.
   /////////////////////////////////////////////////////////////////////

      // MySQL server host:
      $mysql_server = 'db57b.pair.com';

      // username as used in step 2 of INSTALL.mysql:
      $mysql_user = 'at_w';

      // password of above user (or leave blank if none):
      $mysql_pwd = 'At_w_12pass';

      // name of the mysql database
      //  (this used to default to 'wiki' prior to phpwiki-1.2.2)
      $mysql_db = 'at_main1';

      // table name prefix.
      $TableNamePrefix = "wiki_";

      // Names of the tables.
      // You probably don't need to change these.  If you do change
      // them you will also have to make corresponding changes in
      // schemas/schema.mysql before you initialize the database.
      $WikiPageStore = $TableNamePrefix . "wiki";
      $ArchivePageStore = $TableNamePrefix . "archive";
      $WikiLinksStore = $TableNamePrefix . "wikilinks";
      $WikiScoreStore = $TableNamePrefix . "wikiscore";
      $HitCountStore = $TableNamePrefix . "hitcount";

      include "lib/mysql.php";

   /////////////////////////////////////////////////////////////////////
   // Part Three:
   // Miscellaneous
   /////////////////////////////////////////////////////////////////////

   // logo image (path relative to index.php)
   $logo = "";

   // Signature image which is shown after saving an edited page
   // If this is left blank (or unset), the signature will be omitted.
   $SignatureImg = "";

   // this turns on url indicator icons, inserted before embedded links
   //define("USE_LINK_ICONS", 1);
   if (defined('USE_LINK_ICONS') and !defined('DATA_PATH'))
       define("DATA_PATH", dirname($SCRIPT_NAME));

   // date & time formats used to display modification times, etc.
   // formats are given as format strings to PHP date() function
   $datetimeformat = "F j, Y";	// may contain time of day
   $dateformat = "F j, Y";	// must not contain time

   // this defines how many page names to list when displaying
   // the MostPopular pages; the default is to show the 20 most popular pages
   define("MOST_POPULAR_LIST_LENGTH", 20);

   // this defines how many page names to list when displaying related pages
   define("NUM_RELATED_PAGES", 5);

   // number of user-defined external references, i.e. "[1]"
   define("NUM_LINKS", 12);

   // allowed protocols for links - be careful not to allow "javascript:"
   // within a named link [name|uri] one more protocol is defined: phpwiki
   $AllowedProtocols = "http|https|mailto|ftp|news|nntp|gopher";

   // URLs ending with the following extension should be inlined as images
   $InlineImages = "png|jpg|gif";

   // Uncomment this to automatically split WikiWords by inserting spaces.
   // The default is to leave WordsSmashedTogetherLikeSo in the body text.
   //define("AUTOSPLIT_WIKIWORDS", 1);

   // Perl regexp for WikiNames
   // (?<!..) & (?!...) used instead of '\b' because \b matches '_' as well
   //$WikiNameRegexp = "(?<![A-Za-z0-9])([A-Z][a-z]+){2,}(?![A-Za-z0-9])";
   // This should work for all ISO-8859-1 languages:
   $WikiNameRegexp = "(?<![A-Za-z0-9µÀ-ÖØ-öø-ÿ])([A-ZÀ-ÖØ-Þ][a-zµß-öø-ÿ]+){2,}(?![A-Za-z0-9µÀ-ÖØ-öø-ÿ])";

   /////////////////////////////////////////////////////////////////////
   // Part Four:
   // Original pages and layout
   /////////////////////////////////////////////////////////////////////

   // need to define localization function first -- skip this
   if (!function_exists ('gettext')) {
      $lcfile = "locale/$LANG/LC_MESSAGES/phpwiki.php";
      if (file_exists($lcfile)) { include($lcfile); }
      else { $locale = array(); }

      function gettext ($text) { 
         global $locale;
         if (!empty ($locale[$text]))
           return $locale[$text];
         return $text;
      }
   } else {
      // This putenv() fails when safe_mode is on.
      @putenv("LC_ALL=$LANG");
      @putenv("LANG=$LANG");
      @putenv("LANGUAGE=$LANG");
      if (!defined("LC_ALL")) {
         // Backwards compatibility (for PHP < 4.0.5)
         define("LC_ALL", "LC_ALL");
      }
      setlocale(LC_ALL, "$LANG");
      bindtextdomain ("phpwiki", "./locale");
      textdomain ("phpwiki");
   }
   // end of localization function

   // Template files (filenames are relative to script position)
   $templates = array(
   	"BROWSE" =>    gettext("templates/browse.html"),
	"EDITPAGE" =>  gettext("templates/editpage.html"),
	"EDITLINKS" => gettext("templates/editlinks.html"),
	"MESSAGE" =>   gettext("templates/message.html")
	);

   /* WIKI_PGSRC -- specifies the source for the initial page contents
    * of the Wiki.  The setting of WIKI_PGSRC only has effect when
    * the wiki is accessed for the first time (or after clearing the
    * database.) WIKI_PGSRC can either name a directory or a zip file.
    * In either case WIKI_PGSRC is scanned for files --- one file per page.
    *
    * If the files appear to be MIME formatted messages, they are
    * scanned for application/x-phpwiki content-types.  Any suitable
    * content is added to the wiki.
    * The files can also be plain text files, in which case the page name
    * is taken from the file name.
    */

   define('WIKI_PGSRC', gettext("./pgsrc")); // Default (old) behavior.
   //define('WIKI_PGSRC', './wiki.zip'); // New style.

   // DEFAULT_WIKI_PGSRC is only used when the language is *not*
   // the default (English) and when reading from a directory:
   // in that case some English pages are inserted into the wiki as well
   // DEFAULT_WIKI_PGSRC defines where the English pages reside 
   define('DEFAULT_WIKI_PGSRC', "./pgsrc");



   //////////////////////////////////////////////////////////////////////
   // you shouldn't have to edit anyting below this line
   function compute_default_scripturl() {
      global $HTTP_SERVER_VARS, $SERVER_PORT, $SERVER_NAME, $SCRIPT_NAME, $HTTPS;
      if (!ini_get('register_globals')) {
          extract($HTTP_SERVER_VARS);
      }
      
      if (!empty($HTTPS) && $HTTPS != 'off') {
         $proto = 'https';
         $dflt_port = 443;
      }
      else {
         $proto = 'http';
         $dflt_port = 80;
      }
      $port = ($SERVER_PORT == $dflt_port) ? '' : ":$SERVER_PORT";
      return $proto . '://' . $SERVER_NAME . $port . $SCRIPT_NAME;
   }

   if (empty($ScriptUrl)) {
      $ScriptUrl = compute_default_scripturl();
   }
   if (defined('WIKI_ADMIN') && !empty($AdminUrl))
      $ScriptUrl = $AdminUrl;

   $FieldSeparator = "\263";

   if (isset($PHP_AUTH_USER)) {
        $remoteuser = $PHP_AUTH_USER;
   } else {

      // Apache won't show REMOTE_HOST unless the admin configured it
      // properly. We'll be nice and see if it's there.

      getenv('REMOTE_HOST') ? ($remoteuser = getenv('REMOTE_HOST'))
                            : ($remoteuser = getenv('REMOTE_ADDR'));
   }

   // constants used for HTML output. HTML tags may allow nesting
   // other tags always start at level 0
   define("ZERO_LEVEL", 0);
   define("NESTED_LEVEL", 1);

   // constants for flags in $pagehash
   define("FLAG_PAGE_LOCKED", 1);
?>
