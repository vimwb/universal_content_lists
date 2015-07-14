.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


==========
TypoScript
==========

PageTS
======

plugin.tx_universalcontentlists
-------------------------------

+-----------------------------------+---------------+------------------------------------------------------------------------------------------------------+
| Property                          | Data Type     |                                                                                                      |
+===================================+===============+======================================================================================================+
| articlelistColPos                 | integer       | **30** Column Number for Contents to be used in a List.                                                     |
+-----------------------------------+---------------+------------------------------------------------------------------------------------------------------+
| forceCtypeInarticlelistColPos     | boolean       | **0** | 1  If set, connten will be set to universal_content_article in the articlelistColPos Column  |
+-----------------------------------+---------------+------------------------------------------------------------------------------------------------------+


**Example:**

.. code-block:: typoscript

    plugin.tx_universalcontentlists {
       articlelistColPos = 30
       forceCtypeInarticlelistColPos = 1
    }

TCEFORM.tt_content
------------------

To Disable the Warning if you use a Cutom Column please set ``disableNoMatchingValueElement``

**Example:**

.. code-block:: typoscript

    TCEFORM.tt_content.colPos.disableNoMatchingValueElement = 1


Constants
=========

plugin.tx_universalcontentlists
-------------------------------

view
^^^^

		# cat=Content List/file/101; type=string; label=Path to template root (FE)
		templateRootPath =
		# cat=Content List/file/102; type=string; label=Path to template partials (FE)
		partialRootPath = EXT:universal_content_lists/Resources/Private/Partials/
		# cat=Content List/file/103; type=string; label=Path to template layouts (FE)
		layoutRootPath = EXT:universal_content_lists/Resources/Private/Layouts/

+------------------+-----------+----------------------------------------------------------+--------------------------------+
| Property         | Data Type | Value                                                    |  Describtion                   |
+==================+===========+==========================================================+================================+
| templateRootPath | string    | EXT:universal_content_lists/Resources/Private/Templates/ |  Path to template root (FE)    |
+------------------+-----------+----------------------------------------------------------+--------------------------------+
| partialRootPath  | string    | EXT:universal_content_lists/Resources/Private/Partials/  |  Path to template partials (FE)|
+------------------+-----------+----------------------------------------------------------+--------------------------------+
| layoutRootPath   | string    | EXT:universal_content_lists/Resources/Private/Layouts/   |  Path to template layouts (FE) |
+------------------+-----------+----------------------------------------------------------+--------------------------------+


settings
^^^^^^^^

+--------------------+-----------+-----------------------------------------------+-----------------------------------------------------------------------------------------------------+
| Property           | Data Type | Value                                         | Describtion                                                                                         |
+====================+===========+===============================================+=====================================================================================================+
| isTagList          | boolean   | **0** | 1                                     | Display an Article List by Tag                                                                      |
+--------------------+-----------+-----------------------------------------------+-----------------------------------------------------------------------------------------------------+
| forbiddenCTypes    | string    |                                               | Content Types to exclude from Content Lists (Comma Separated)                                       |
+--------------------+-----------+-----------------------------------------------+-----------------------------------------------------------------------------------------------------+
| selectMode         | string    | currentPage | pid  | global | searchresults   | Select Mode for Articles                                                                            |
+--------------------+-----------+-----------------------------------------------+-----------------------------------------------------------------------------------------------------+
| storagePIDs        | string    |                                               | select Articles from this Page IDs (Comma Separated)                                                |
+--------------------+-----------+-----------------------------------------------+-----------------------------------------------------------------------------------------------------+
| limitToCategories  | string    |                                               | select Articles with this Category IDs (Comma Separated)                                            |
+--------------------+-----------+-----------------------------------------------+-----------------------------------------------------------------------------------------------------+
| limitTocColPosIDs  | string    |                                               | select Articles with this Column IDs (Comma Separated, Default articlelistColPos from PageTS is 30) |
+--------------------+-----------+-----------------------------------------------+-----------------------------------------------------------------------------------------------------+
| usePaging          | boolean   | **0** | 1                                     | show the Paginagtion under the List                                                                 |
+--------------------+-----------+-----------------------------------------------+-----------------------------------------------------------------------------------------------------+
| itemsPerPage       | integer   |                                               | How many Articles are visible on one Page                                                           |
+--------------------+-----------+-----------------------------------------------+-----------------------------------------------------------------------------------------------------+
| sortfield          | boolean   | **sorting** | colPos | date | crdate | tstamp | Sort Articles by this Field                                                                         |
+--------------------+-----------+-----------------------------------------------+-----------------------------------------------------------------------------------------------------+
| sorting            | string    | **ASC** | DESC                                | Sorting Order: Order for Articles                                                                   |
+--------------------+-----------+-----------------------------------------------+-----------------------------------------------------------------------------------------------------+
| taglistPID         | integer   |                                               | ID of Page that displays the ArticleList by Tag                                                     |
+--------------------+-----------+-----------------------------------------------+-----------------------------------------------------------------------------------------------------+
| searchlistPID      | integer   |                                               | ID of Page that displays the Search Results List                                                    |
+--------------------+-----------+-----------------------------------------------+-----------------------------------------------------------------------------------------------------+
| getRelatedFromTags | boolean   | **0** | 1                                     | Load related Articles List in Detailview from Tags                                                  |
+--------------------+-----------+-----------------------------------------------+-----------------------------------------------------------------------------------------------------+
| relatedLimit       | integer   |                                               | Limit related List items                                                                            |
+--------------------+-----------+-----------------------------------------------+-----------------------------------------------------------------------------------------------------+

list
^^^^

        # cat=Content List/list/100; type=string; label=Thumbnail Width: MaxWidth of Thumbnails in List
        listImageMaxWidth = 620

        # cat=Content List/list/101; type=string; label=Thumbnail Width: MaxHeight of Thumbnails in List
        listImageMaxHeight = 300


show
^^^^

        # cat=Content List/show/100; type=string; label=Thumbnail Width: MaxWidth of Thumbnails in Detail View (Show)
        showImageMaxWidth = 620

        # cat=Content List/show/100; type=string; label=Thumbnail Height: MaxHeight of Thumbnails in Detail View (Show)
        showImageMaxHeight = 465


imageSizes
^^^^^^^^^^

        # cat=Content imageSizes/100; type=string; label=Bigger Width: default 1200
        biggerwidth = 1200
        # cat=Content imageSizes/101; type=string; label=Bigger Height
        biggerheight = 900
        # cat=Content imageSizes/102; type=string; label=Bigger Width: default 940
        largewidth = 940
        # cat=Content imageSizes/103; type=string; label=Bigger Height
        largeheight = 705
        # cat=Content imageSizes/104; type=string; label=Bigger Width: default 720
        mediumwidth = 720
        # cat=Content imageSizes/105; type=string; label=Bigger Height
        mediumheight = 540
        # cat=Content imageSizes/106; type=string; label=Bigger Width: default 610
        smallwidth = 620
        # cat=Content imageSizes/107; type=string; label=Bigger Height
        smallheight = 465
