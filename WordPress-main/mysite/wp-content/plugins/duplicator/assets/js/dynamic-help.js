jQuery(document).ready(function ($) {
    Duplicator.Help.Data = null;

    Duplicator.Help.isDataLoaded = function() {
        return Duplicator.Help.Data !== null;
    };

    Duplicator.Help.ToggleCategory = function(categoryHeader) {
        $(categoryHeader).find(".fa-angle-right").toggleClass("fa-rotate-90");
        $(categoryHeader).siblings(".duplicator-help-article-list").slideToggle();
        $(categoryHeader).siblings(".duplicator-help-category-list").slideToggle();
    };

    Duplicator.Help.Search = function(search) {
        let results   = $("#duplicator-help-search-results");
        let noResults = $("#duplicator-help-search-results-empty");
        let context   = $("#duplicator-context-articles");
        let articles  = $(".duplicator-help-article");
        let regex     = Duplicator.Help.GetRegex(search);

        if (search.length === 0 && regex === null) {
            context.show();
            results.hide();
            noResults.hide();
            return;
        }

        let found    = false;
        let foundIds = [];

        context.hide();
        results.empty();

        articles.each(function() {
            let article = $(this);
            let id      = article.data("id");
            let title   = article.find("a").text().toLowerCase();

            if (title.search(regex) !== -1 && foundIds.indexOf(id) === -1) {
                found = true;
                results.append(article.clone());
                foundIds.push(id);
            }
        });

        if (found) {
            results.show();
            noResults.hide();
        } else {
            results.hide();
            noResults.show();
        }
    };

    Duplicator.Help.Load = function(url) {
        if (Duplicator.Help.isDataLoaded()) {
            return;
        }

        $.ajax({
            type: 'GET',
            url: url,
            beforeSend: function(xhr) {
                Duplicator.Util.ajaxProgressShow();
            },
            success: function (result) {
                Duplicator.Help.Data = result;
                //because ajax is async we need to open the modal here for first time
                Duplicator.Help.Display();
            },
            error: function (result) {
                Duplicator.addAdminMessage('Failed to load help content!', 'error');
            },
            complete: function () {
                Duplicator.Util.ajaxProgressHide();
            },
        });
    };

    Duplicator.Help.Display = function() {
        if (!Duplicator.Help.isDataLoaded()) {
            throw 'Duplicator.Help.Data is null';
        }

        let box = new DuplicatorModalBox({
            htmlContent: Duplicator.Help.Data,
        });
        box.open();
    };

    Duplicator.Help.GetRegex = function(search = '') {
        let regexStr = '';
        let regex = null;

        if (search.length < 1) {
            return null;
        }

        $.each(search.split(' '), function(key, value) {
            //escape regex
            value = value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

            if (value.length > 1) {
                regexStr += '(?=.*'+value+')';
            }
        });

        regex = new RegExp(regexStr, 'i');
        return regex;
    };


    $("body").on("click", ".duplicator-help-category header", function() {
        Duplicator.Help.ToggleCategory(this);
    });

    $("body").on("keyup", "#duplicator-help-search input", function() {
        Duplicator.Help.Search($(this).val().toLowerCase());
    });
});
