###########################################
#### CTYPE: ARTICLE ####
###########################################

tt_content.universal_content_article = COA
tt_content.universal_content_article {

	20 = FLUIDTEMPLATE
	20 {
		file = {$plugin.tx_universalcontentlists.view.templateRootPath}ContentElements/Article.html
        partialRootPath = {$plugin.tx_universalcontentlists.view.partialRootPath}
        layoutRootPath = {$plugin.tx_universalcontentlists.view.layoutRootPath}


        variables {

            listImageMaxWidth = TEXT
            listImageMaxWidth.value = {$plugin.tx_universalcontentlists.list.listImageMaxWidth}

            showImageMaxWidth = TEXT
            showImageMaxWidth.value = {$plugin.tx_universalcontentlists.show.showImageMaxWidth}

            biggerwidth = TEXT
            biggerwidth.value = {$plugin.tx_universalcontentlists.imageSizes.biggerwidth}

            largewidth = TEXT
            largewidth.value = {$plugin.tx_universalcontentlists.imageSizes.largewidth}

            mediumwidth = TEXT
            mediumwidth.value = {$plugin.tx_universalcontentlists.imageSizes.mediumwidth}

            smallwidth = TEXT
            smallwidth.value = {$plugin.tx_universalcontentlists.imageSizes.smallwidth}
        }
	}
}