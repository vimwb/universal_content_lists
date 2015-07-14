<?php

define('TYPO3_MODE', 'BE');
define('TYPO3_cliMode', TRUE);
define('TYPO3_OS', stristr(PHP_OS, 'win') && !stristr(PHP_OS, 'darwin') ? 'WIN' : '');
define('PATH_thisScript', str_replace('//', '/', str_replace('\\', '/', (php_sapi_name() == 'cgi' || php_sapi_name() == 'isapi' || php_sapi_name() == 'cgi-fcgi') && ($_SERVER['ORIG_PATH_TRANSLATED'] ? $_SERVER['ORIG_PATH_TRANSLATED'] : $_SERVER['PATH_TRANSLATED']) ? ($_SERVER['ORIG_PATH_TRANSLATED'] ? $_SERVER['ORIG_PATH_TRANSLATED'] : $_SERVER['PATH_TRANSLATED']) : ($_SERVER['ORIG_SCRIPT_FILENAME'] ? $_SERVER['ORIG_SCRIPT_FILENAME'] : $_SERVER['SCRIPT_FILENAME']))));
define('PATH_site', @preg_replace('/[^/]*.[^/]*$/', '', dirname(dirname(PATH_thisScript))));
define('PATH_typo3', PATH_site . 'typo3/');
define('PATH_typo3conf', PATH_site . 'typo3conf/');
define('PATH_t3lib', PATH_site . 't3lib/');


/**
 *
 *
 * @package universal_content_lists
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_UniversalContentLists_Controller_AdminController extends Tx_Extbase_MVC_Controller_ActionController {


    /**
     * Constructor
     */
    public function __construct() {

    }

    /**
     * Replace all linebreaks with one whitespace.
     *
     * @access public
     * @param string $string
     *   The text to be processed.
     * @return string
     *   The given text without any linebreaks.
     */
    function replace_newline($string) {

        return (string)str_replace(array("\r", "\r\n", "\n"), '', $string);
    }

    /**
     * rand_string
     *
     * @param int $length
     * @return string
     */
    public function rand_string($length) {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }

    /**
     * action migrateData
     *
     * @return void
     */
    public function migrateDataAction() {

        t3lib_div::devLog('migrateDataAction', " universal_content_lists" ,-1, array());

        #\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("parameters"=>$parameters, "parentObject"=>$parentObject));

    }

    /**
     * action doMigrateData
     *
     * @return void
     */
    public function doMigrateDataAction() {
        t3lib_div::devLog('doMigrateDataAction', " universal_content_lists", -1, array());

        #$this->migrateEmails();

        t3lib_div::devLog("doMigrateDataAction Data Migration complete", " universal_content_lists" ,-1 );
        $this->flashMessageContainer->add('Data Migration complete');
      #  $this->redirect('migrateData');
    }

    /**
     * action doMigrateData
     *
     * @return resource
     */
    private function connectJoomlaDB() {
        $JOOMLA_LINK =  mysql_connect('sql155.your-server.de', 'skagke_2', 'wBM3na52');

        // Umlaute in UTF-8
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $JOOMLA_LINK);

        if ($JOOMLA_LINK) {
            mysql_select_db ("skagke_db2",$JOOMLA_LINK);
        }
        else
        {
            die('Verbindung nicht möglich : ' . mysql_error());
        }

        return $JOOMLA_LINK;

        // mysql_close($link);
    }

	private function migrateEmails() {

		$this->connectJoomlaDB();

		$queryEmails = "SELECT * FROM j25_acymailing_subscriber";
		$resultEmails = mysql_query($queryEmails);

		if (mysql_num_rows($resultEmails) == 0) {
			echo "Keine Zeilen gefunden";
			exit;
		} else {
			while ($row = mysql_fetch_assoc($resultEmails)) {
				#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("row"=>$row));

				/**
				 * @var t3lib_DB $TYPO3_DB
				 */
				$TYPO3_DB = $GLOBALS['TYPO3_DB'];

				$deleted = $row['enabled'] == 1 ? 0 : 1;
				$hidden = $row['confirmed'] == 1 ? 0 : 1;

				$queryInsertEmail = "INSERT INTO `tt_address` (`pid`, `tstamp`,  `hidden`,    `name`, 	                                `gender`, `first_name`, `middle_name`, `last_name`, `birthday`, `title`, `email`,            `phone`, `mobile`, `www`, `address`, `building`, `room`, `company`, `city`, `zip`, `region`, `country`, `image`, `fax`, `deleted`,   `description`, `addressgroup`, `module_sys_dmail_category`, `module_sys_dmail_html`, `tx_directmailsubscription_localgender`) VALUES
					                                           (40, ".time().", ".$hidden.", '".mysql_real_escape_string($row['name'])."', '',       '',           '',            '',          0,          '',      '".$row['email']."', '',     '',       '',    '',        '',         '',      '',       '',     '',    '',      '',         '',      '',   ".$deleted.", '',            0,              0,                           ".$row['html'].",                        '')";

				$resInsert = $TYPO3_DB->sql_query($queryInsertEmail);
				$TYPO3_DB->sql_free_result($resInsert);

			}
			mysql_free_result($resultEmails);
		}
	}

    private function removeUnusedTags() {

        /**
         * @var t3lib_DB $TYPO3_DB
         */
        $TYPO3_DB = $GLOBALS['TYPO3_DB'];

        $query = "SELECT uid, headline FROM `tx_universalcontentlists_domain_model_tag`";
        $result = $TYPO3_DB->sql_query($query);

        $tagswithoutRelations = array();
        $tagswithRelations = array();


        while ($row = $TYPO3_DB->sql_fetch_assoc($result)) {

            $firstvalue = $row['uid'];

            $queryCheckRelation = "SELECT uid, header, tx_universalcontentlists_tags FROM `tt_content` ";
            $queryCheckRelation .= "WHERE ";
            $queryCheckRelation .= "tx_universalcontentlists_tags = '" . $firstvalue."'"; // 4 nur der wert
            $queryCheckRelation .= " OR tx_universalcontentlists_tags LIKE '%," . $firstvalue . ",%'"; // 10,4,14,15  Wert ZWISCHEN anderen
            $queryCheckRelation .= " OR tx_universalcontentlists_tags LIKE '". $firstvalue . ",%'"; // 4,14,15 // wert  VOR anderen als ERSTER
            $queryCheckRelation .= " OR tx_universalcontentlists_tags LIKE '%," . $firstvalue."' "; // 4,14,15 // wert  NACH anderen als LETZTER


            #\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("queryCheckRelation"=>$queryCheckRelation));
            $resultqueryCheckRelation = $TYPO3_DB->sql_query($queryCheckRelation);

            if ($TYPO3_DB->sql_num_rows($resultqueryCheckRelation) == 0) {
                #\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("no relations for"=>$row));
                $tagswithoutRelations[] = array($row,$queryCheckRelation);

                $queryDel = "DELETE  FROM `tx_universalcontentlists_domain_model_tag` WHERE uid = ".$row['uid'];
                #$resultDel = $TYPO3_DB->sql_query($queryDel);

            } else {
                $tagswithRelations[] = array($row,$queryCheckRelation);
            }
        }


        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("tagswithoutRelations"=>$tagswithoutRelations,"tagswithRelations"=>$tagswithRelations));
    }

    private function migrateUserGroups() {

        $this->connectJoomlaDB();

        $queryGroups = "SELECT * FROM j25_usergroups";
        $resultGroups = mysql_query($queryGroups);

        if (mysql_num_rows($resultGroups) == 0) {
            echo "Keine Zeilen gefunden";
            exit;
        } else {

            while ($row = mysql_fetch_assoc($resultGroups)) {
                \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("row"=>$row));

                /**
                 * @var t3lib_DB $TYPO3_DB
                 */
                $TYPO3_DB = $GLOBALS['TYPO3_DB'];

                $queryInsertGroup = "INSERT INTO `be_groups` (
                                        `uid`,
                                        `pid`,
                                        `tstamp`,
                                        `title`,
                                        `non_exclude_fields`,
                                        `explicit_allowdeny`,
                                        `allowed_languages`,
                                        `custom_options`,
                                        `db_mountpoints`,
                                        `pagetypes_select`,
                                        `tables_select`,
                                        `tables_modify`,
                                        `crdate`,
                                        `cruser_id`,
                                        `groupMods`,
                                        `file_mountpoints`,
                                        `hidden`,
                                        `description`,
                                        `lockToDomain`,
                                        `deleted`,
                                        `TSconfig`,
                                        `subgroup`,
                                        `hide_in_lists`,
                                        `workspace_perms`,
                                        `file_permissions`,
                                        `category_perms`
                                        )
                                    VALUES
                                        (
                                        ".$row['id'].",
                                        0,
                                        ".time().",
                                        '".$row['title']."',
                                        'tx_rtehtmlarea_acronym:type,tx_bootstrappackage_accordion_item:sys_language_uid,tx_bootstrappackage_accordion_item:starttime,tx_bootstrappackage_accordion_item:endtime,tx_bootstrappackage_accordion_item:l10n_parent,tx_bootstrappackage_accordion_item:hidden,tt_address:description,tt_address:image,tt_address:module_sys_dmail_html,tt_address:fax,tt_address:birthday,tt_address:addressgroup,tt_address:module_sys_dmail_category,tt_address:country,tt_address:tx_directmailsubscription_localgender,tt_address:mobile,tt_address:company,tt_address:region,tt_address:title,tt_address:hidden,tt_address:www,tt_address_group:description,tt_address_group:sys_language_uid,tt_address_group:title,tt_address_group:l18n_parent,tt_address_group:hidden,tt_address_group:fe_group,tt_address_group:parent_group,pages_language_overlay:description,pages_language_overlay:nav_title,pages_language_overlay:hidden,pages_language_overlay:starttime,pages_language_overlay:endtime,tx_bootstrappackage_carousel_item:link,tx_bootstrappackage_carousel_item:sys_language_uid,tx_bootstrappackage_carousel_item:starttime,tx_bootstrappackage_carousel_item:endtime,tx_bootstrappackage_carousel_item:l10n_parent,tx_bootstrappackage_carousel_item:hidden,sys_file_metadata:categories,sys_file_metadata:title,sys_file_reference:alternative,sys_file_reference:description,sys_file_reference:link,sys_file_reference:title,sys_file_collection:sys_language_uid,sys_file_collection:starttime,sys_file_collection:endtime,sys_file_collection:l10n_parent,sys_file_collection:hidden,sys_dmail:issent,sys_dmail:scheduled,sys_dmail:renderedsize,sys_domain:redirectHttpStatusCode,sys_domain:forced,sys_domain:hidden,sys_domain:prepend_params,sys_category:sys_language_uid,sys_category:starttime,sys_category:endtime,sys_category:l10n_parent,sys_category:hidden,pages:backend_layout_next_level,pages:backend_layout,pages:description,pages:nav_hide,pages:nav_title,pages:hidden,pages:starttime,pages:endtime,pages:doktype,pages:shortcut_mode,tt_content:pi_flexform;tt_address_pi1;sDEF;pages,tt_content:pi_flexform;tt_address_pi1;sDEF;singleRecords,tt_content:pi_flexform;universalcontentlists_contentlist;settings;settings.storagePIDs,tt_content:pi_flexform;universalcontentlists_contentlist;settings;settings.limitToCategories,tt_content:pi_flexform;universalcontentlists_contentlist;settings;settings.limitTocColPosIDs,tt_content:altText,tt_content:date,tt_content:tx_universalcontentlists_exclude_recursive,tt_content:module_sys_dmail_category,tt_content:categories,tt_content:header_link,tt_content:image_link,tt_content:recursive,tt_content:rte_enabled,tt_content:tx_universalcontentlists_recursive,tt_content:colPos,tt_content:sys_language_uid,tt_content:starttime,tt_content:endtime,tt_content:titleText,tt_content:l18n_parent,tt_content:hidden,tx_universalcontentlists_domain_model_tag:sys_language_uid,tx_universalcontentlists_domain_model_tag:starttime,tx_universalcontentlists_domain_model_tag:endtime,tx_universalcontentlists_domain_model_tag:l10n_parent,tx_universalcontentlists_domain_model_tag:hidden,fe_users:address,fe_users:lockToDomain,fe_users:image,fe_users:tx_extbase_type,fe_users:email,fe_users:module_sys_dmail_html,fe_users:fax,fe_users:company,fe_users:disable,fe_users:module_sys_dmail_category,fe_users:country,fe_users:lastlogin,fe_users:middle_name,fe_users:last_name,fe_users:name,fe_users:module_sys_dmail_newsletter,fe_users:zip,fe_users:city,fe_users:starttime,fe_users:endtime,fe_users:telephone,fe_users:title,fe_users:TSconfig,fe_users:first_name,fe_users:www,fe_groups:lockToDomain,fe_groups:tx_extbase_type,fe_groups:hidden,fe_groups:TSconfig,fe_groups:subgroup', 'tt_content:CType:bootstrap_package_carousel:ALLOW,tt_content:CType:universal_content_article:ALLOW,tt_content:CType:universal_content_banner:ALLOW,tt_content:CType:header:ALLOW,tt_content:CType:text:ALLOW,tt_content:CType:textpic:ALLOW,tt_content:CType:image:ALLOW,tt_content:CType:list:ALLOW,tt_content:CType:html:ALLOW,tt_content:list_type:universalcontentlists_contentlist:ALLOW,tt_content:list_type:21:ALLOW,tx_bootstrappackage_carousel_item:item_type:header:ALLOW,tx_bootstrappackage_carousel_item:item_type:textandimage:ALLOW,tx_bootstrappackage_carousel_item:item_type:html:ALLOW',
                                        '',
                                        '',
                                        '1',
                                        '1,4,254',
                                        '',
                                        'pages,sys_category,sys_collection,sys_file,sys_file_collection,sys_file_metadata,sys_file_reference,sys_file_storage,fe_groups,fe_users,pages_language_overlay,sys_domain,tt_content,tx_rtehtmlarea_acronym,sys_note,sys_dmail,sys_dmail_category,sys_dmail_group,tx_bootstrappackage_accordion_item,tx_bootstrappackage_carousel_item,tt_address,tx_universalcontentlists_domain_model_tag,tt_address_group',
                                        ".time().",
                                        1,
                                        'web,web_layout,web_ViewpageView,web_list,file,file_list,user,user_setup,txdirectmailM1,txdirectmailM1_txdirectmailM2,txdirectmailM1_txdirectmailM3,txdirectmailM1_txdirectmailM4,txdirectmailM1_txdirectmailM5,help_DocumentationDocumentation,help_AboutAbout,help_cshmanual,help_AboutmodulesAboutmodules',
                                        '1',
                                        0,
                                        '',
                                        '',
                                        0,
                                        '# Cache enable\r\noptions.clearCache.all = 1\r\noptions.clearCache.pages = 1\r\noptions.enableBookmarks = 1\r\n\r\nadmPanel {\r\n	enable {\r\n		preview = 1\r\n		cache = 0\r\n		publish = 0\r\n		edit = 0\r\n		info = 0\r\n	}\r\n	hide = 1\r\n}',
                                        '',
                                        0,
                                        0,
                                        'readFolder,writeFolder,addFolder,renameFolder,moveFolder,copyFolder,deleteFolder,recursivedeleteFolder,readFile,writeFile,addFile,renameFile,moveFile,copyFile,unzipFile,deleteFile',
                                        ''
                                        )";

                $resInsert = $TYPO3_DB->sql_query($queryInsertGroup);
                $TYPO3_DB->sql_free_result($resInsert);

            }
            mysql_free_result($resultGroups);
        }
    }

    private function migrateUsers() {

        $this->connectJoomlaDB();

        $query = "SELECT * FROM j25_users";
        $result = mysql_query($query);

        if (mysql_num_rows($result) == 0) {
            echo "Keine Zeilen gefunden";
            exit;
        } else {

            while ($row = mysql_fetch_assoc($result)) {
                \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("row"=>$row));

                /**
                 * @var t3lib_DB $TYPO3_DB
                 */
                $TYPO3_DB = $GLOBALS['TYPO3_DB'];

                $queryInsert = "INSERT INTO `be_users` (`uid`, `pid`, `tstamp`, `username`, `password`, `admin`, `usergroup`, `disable`, `starttime`, `endtime`, `lang`, `email`, `db_mountpoints`, `options`, `crdate`, `cruser_id`, `realName`, `userMods`, `allowed_languages`, `uc`, `file_mountpoints`, `workspace_perms`, `lockToDomain`, `disableIPlock`, `deleted`, `TSconfig`, `lastlogin`, `createdByAction`, `usergroup_cached_list`, `workspace_id`, `workspace_preview`, `file_permissions`, `category_perms`) VALUES
                                                            (".$row['id'].", 0, ".time().", '".$row['username']."', '".$row['password']."', 0, '100', 0, 0, 0, '', '".$row['email']."', '', 0, ".time().", 0, '', '', '', '', '', 1, '', 0, 0, '', 0, 0, '', 0, 1, 'addFile,readFile,writeFile,copyFile,moveFile,renameFile,unzipFile,deleteFile,addFolder,readFolder,writeFolder,moveFolder,renameFolder,deleteFolder', ''
                                      )";

                $resInsert = $TYPO3_DB->sql_query($queryInsert);
                $TYPO3_DB->sql_free_result($resInsert);

            }
            mysql_free_result($result);
        }
    }

    private function migrateTags() {

        $this->connectJoomlaDB();

        $query = "SELECT * FROM j25_k2_tags";
        $result = mysql_query($query);

        if (mysql_num_rows($result) == 0) {
            echo "Keine Zeilen gefunden";
            exit;
        } else {

            while ($row = mysql_fetch_assoc($result)) {

                if( $row['id'] == 393 ){
                    \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("row"=>$row));
                }

                /**
                 * @var t3lib_DB $TYPO3_DB
                 */
                $TYPO3_DB = $GLOBALS['TYPO3_DB'];

                $hidden = $row['published'] == 1 ? 0 : 1;

                $queryInsert = "INSERT INTO `tx_universalcontentlists_domain_model_tag`
                  (`uid`, `pid`, `headline`, `tstamp`, `crdate`, `cruser_id`, `deleted`, `hidden`, `starttime`, `endtime`, `t3ver_oid`, `t3ver_id`, `t3ver_wsid`, `t3ver_label`, `t3ver_state`, `t3ver_stage`, `t3ver_count`, `t3ver_tstamp`, `t3ver_move_id`, `sorting`, `t3_origuid`, `sys_language_uid`, `l10n_parent`, `l10n_diffsource`, `taglist_pid`) VALUES
                  (".$row['id'].", 34, '".mysql_real_escape_string($row['name'])."', ".time().", ".time().", 1, 0, ".$hidden.", 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0)";

               $resInsert = $TYPO3_DB->sql_query($queryInsert);
               $TYPO3_DB->sql_free_result($resInsert);

            }
            mysql_free_result($result);
        }
    }

    private function migrateArticles() {

        $categories2PageIDs = array(
            "2" => array("pid" => 16, "name" =>"Türkei"),
            "3" => array( "pid" => 14, "name" =>"Grenz- & Migrationspolitik"),
            "7" => array( "pid" => 19, "name" =>"Neues aus dem Ausschuss"),
            "15" => array( "pid" => 23, "name" =>"Neues aus dem LIBE-Ausschuss"),
            "16" => array( "pid" => 22, "name" =>"Migrations- & Asylpolitik"),
            "26" => array( "pid" => 7, "name" =>"Presse"),
            "28" => array( "pid" => 42, "name" =>"Blog"),
            "34" => array( "pid" => 43, "name" =>"Lokales"),
            "45" => array( "pid" => 21, "name" =>"EU-Grenzregime"),
            "51" => array( "pid" => 17, "name" =>"Handelsabkommen")
        );


        $this->connectJoomlaDB();

        $idList = "885,1041,394,1036,1008,1013,1028,605,887,1053,638,564,733,673,905,294,412,462,1218";
        $where = "j25_k2_items.id IN (1008)";

        #$where = "j25_k2_items.created > DATE('2014-06-30 00:00:00') AND j25_k2_items.published = 1 AND j25_k2_items.language = 'en-GB'";
        #$where = "j25_k2_items.created > DATE('2014-06-30 00:00:00') AND j25_k2_items.published = 1 AND (j25_k2_items.language = '*' OR j25_k2_items.language = 'de-DE' )";

        $query = "SELECT
        j25_k2_items.*,
        GROUP_CONCAT(j25_k2_tags_xref.tagID ORDER BY j25_k2_tags_xref.tagID ASC SEPARATOR ','  )  as tags
        FROM
        j25_k2_items
        LEFT JOIN
        j25_k2_tags_xref ON (j25_k2_items.id = j25_k2_tags_xref.itemID )
        WHERE
        $where
        GROUP BY
        j25_k2_items.id
        LIMIT 0,40";

        $result = mysql_query($query);

        if (mysql_num_rows($result) == 0) {
            echo "Keine Zeilen gefunden";
            exit;
        } else {

            while ($row = mysql_fetch_assoc($result)) {

                #\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("row"=>$row));

                /**
                 * @var t3lib_DB $TYPO3_DB
                 */
                $TYPO3_DB = $GLOBALS['TYPO3_DB'];

                $pid = $categories2PageIDs[$row['catid']]['pid'];

                if ( $pid == NULL || $pid == ''){
                    $pid = 42; // blog
                }

                $sys_language_uid = $row['language'] == "en-GB" ? 1 : 0;

               $queryInsert = "INSERT INTO `tt_content` (`pid`, `tstamp`, `CType`,                      `header`,                                        `bodytext`,                                    `image`,  `imagecaption`,                                          `tx_universalcontentlists_video`,            `starttime`,                                 `endtime`, `colPos`, `date`,                              `multimedia`, `sys_language_uid`,     `crdate`,                      `cruser_id`,            `tx_universalcontentlists_short`, `tx_universalcontentlists_tags`) VALUES
                                                        ($pid, ".time().", 'universal_content_article', '".mysql_real_escape_string($row['title'])."', '".mysql_real_escape_string($row['fulltext'])."', '0',      '".mysql_real_escape_string($row['image_caption'])."',  '".mysql_real_escape_string($row['video'])."', ".strtotime($row['publish_up']).",          0,         30,       '".strtotime($row['publish_up'])."', '',           ".$sys_language_uid.", ".strtotime($row['created']).", ".$row['created_by'].", '".mysql_real_escape_string($row['introtext'])."',           '".$row['tags']."' )";

                $resInsert = $TYPO3_DB->sql_query($queryInsert);
                $TYPO3_DB->sql_free_result($resInsert);

                $lastUID = $TYPO3_DB->sql_insert_id();

                $image = md5("Image" . $row['id'])."_XL.jpg";
                $this->addFile($image, $lastUID, $pid, mysql_real_escape_string($row['image_caption']));

                #\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("image"=>$image));

            }
            mysql_free_result($result);
        }
    }


    private function addFile($imageName, $tt_content_uid, $pages_uid, $imageCaption) {

        $sourceURL = "http://ska-keller.de/media/k2/items/cache/".$imageName;
        $tmpImage = sys_get_temp_dir().'/'.$imageName;

        $file = @file_get_contents($sourceURL);

        if ( $file )
        {
            #\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("sourceURL"=>$sourceURL));

            file_put_contents($tmpImage, $file);

            /** @var $storageRepository \TYPO3\CMS\Core\Ressources\StorageRepository */
            $storageRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\StorageRepository');

            /**
             * @var \TYPO3\CMS\Core\Resource\ResourceStorage $storage
             */
            $storage = $storageRepository->findByUid('1');

            // this will already handle the moving of the file to the storage:
            $newFileObject = $storage->addFile(
                $tmpImage, $storage->getFolder("material/artikel"), $imageName
            );


            $data = array();
            $data['sys_file_reference']['NEW1234'] = array(
                'uid_local' => $newFileObject->getUid(),
                'uid_foreign' => $tt_content_uid, // uid of your content record
                'tablenames' => 'tt_content',
                'fieldname' => 'image',
                'pid' => $pages_uid, // parent id of the parent page
                'table_local' => 'sys_file',

                'title' => $imageCaption,
                'description' => $imageCaption,
                'alternative' => $imageCaption,

            );
            $data['tt_content'][$tt_content_uid] = array('image' => 'NEW1234'); // set to the number of images

            /** @var \TYPO3\CMS\Core\DataHandling\DataHandler $tce */
            $tce = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\DataHandling\DataHandler'); // create TCE instance
            $tce->start($data, array());
            $tce->process_datamap();

            if ($tce->errorLog)
            {
               \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("tce error"=>$tce->errorLog));
            }
            else
            {
                #\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($data);
            }
            // Do not directly insert a record into sys_file_reference, as this bypasses all sanity checks and automatic updates done

        }

    }
}

?>