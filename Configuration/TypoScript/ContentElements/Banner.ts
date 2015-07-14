###########################################
#### CTYPE: BANNER ####
###########################################

tt_content.universal_content_banner = COA
tt_content.universal_content_banner {

	20 = FLUIDTEMPLATE
	20 {
		file = {$plugin.tx_universalcontentlists.view.templateRootPath}ContentElements/Banner.html
        partialRootPath = {$plugin.tx_universalcontentlists.view.partialRootPath}
        layoutRootPath = {$plugin.tx_universalcontentlists.view.layoutRootPath}

        variables {

            listImageMaxWidth = TEXT
            listImageMaxWidth.value = {$plugin.tx_universalcontentlists.list.listImageMaxWidth}

            showImageMaxWidth = TEXT
            showImageMaxWidth.value = {$plugin.tx_universalcontentlists.show.showImageMaxWidth}

            biggerwidth = TEXT
            biggerwidth.value = {$page.breakpoints.biggerwidth}

            largewidth = TEXT
            largewidth.value = {$page.breakpoints.largewidth}

            mediumwidth = TEXT
            mediumwidth.value = {$page.breakpoints.mediumwidth}

            smallwidth = TEXT
            smallwidth.value = {$page.breakpoints.smallwidth}
        }
	}
}