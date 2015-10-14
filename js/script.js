$(document).ready(function() {

    function printAlert(tag, parent) {
        var url = window.location.href.split('?')[0];
        var alert = $("<p></p>").text("Filter: ");
        var link = $('<a>', {
            text: tag,
            href: url,
            class: 'removeTag'
        }).appendTo(alert);
        alert.addClass("alert");
        alert.addClass("alert-info");
        parent.prepend(alert)
    }

    function loadTagList() {
        $.get("tagList.php")
            .done(function(data) {
                $("#tag-column").html(data);
                $(".tag-list").stupidtable();
                $(".clickable").click(function() {
                    var allRows = $(".tag-list").children('tbody').children('tr');
                    var row = $(this).closest('tr')
                    var tag = row.children().first().text();
                    loadTaggedLinks(tag);
                    allRows.each(function(key, value) {
                        if ($(value).hasClass("highlightedTag")) {
                            $(value).removeClass("highlightedTag")
                        }
                    })
                    row.addClass("highlightedTag");
                })
                $(window).on("resize", function() {
                    checkAndAdjustTagListStickiness();
                }).resize();
            })
    }


    function checkAndAdjustTagListStickiness() {
        var tagListHeight = $(".tag-list").height();
        var viewportHeight = $(window).height();
        if (tagListHeight < viewportHeight) {
            $(".tag-list").sticky({
                topSpacing: 10
            });
        } else {
            $(".tag-list").unstick()
        }
    }




    function fillEntries(data) {
        $(".entries").html(data);
        addFunctionsToEntryInterface();
    }


    function loadAllLinks() {
        $.get("allLinks.php")
            .done(function(data) {
                fillEntries(data);
            })
    }

    function loadTaggedLinks(tag) {
        $.get("tagLinks.php", {
                tag: tag
            })
            .done(function(data) {
                fillEntries(data);
                printAlert(tag, $(".entries"))
            })
    }


    loadLinks()

    function updateUserCount() {
        var count = $("#userCount");
        $.get("getCount.php")
            .done(function(data) {
                count.text(data)
            })
    }

    function loadLinks() {
        loadAllLinks()
        loadTagList();
        updateUserCount();
    }







    $("#tagForm").tagit({
        singleField: true,
    });

    $("#showPanel").click(function() {
        if ($(window).width() <= 767) {
            $("html, body").animate({
                scrollTop: 0
            }, "fast");
        }
        $(".addURLPanel").slideToggle(function() {
            if ($(".addURLPanel").is(":visible")) {
                console.log("visible")
                $("#url").focus();
            }
        });

    })


    function addFunctionsToEntryInterface() {
        $(".btn-circle").tooltip({
            animation: true,
        });
        $(".glyphicon-remove").tooltip({
            animation: true,
        });

        $(".glyphicon-edit").tooltip({
            animation: true,
        });

        $(".btn-circle").tooltip({
            animation: true,
        });

        activateAllEditButtons()

        $(".entry").hover(function() {
            $(this).children(".edit-links").css(
                "color", "#3A3A3A")
        }, function() {
            $(this).children(".edit-links").css(
                "color", "#EBEBEB");
        })

        $(".glyphicon-remove").on('click', function() {
            var parent = $(this).parents(".entry");
            if (confirm("Are you sure?")) {
                var linkID = this.id;
                $.post("delete.php", {
                        linkID: linkID
                    })
                    .done(function() {
                        loadLinks()
                    })
            }
        })

        $(".tag").on('click', function() {
            var tagText = $(this).text();
            loadTaggedLinks(tagText);
        })

    }


    function activateAllEditButtons() {
        $(".glyphicon-edit").click(function() {
            var entry = $(this).parents(".entry");
            makeEntryEditable(entry)
            $(".glyphicon-edit").off('click')
        })
    }

    function makeEntryEditable(entry) {
        function generateCancelButton() {
            var icon = $("<span/>", {
                class: "glyphicon glyphicon-remove",
            })
            var buttonCancel = $("<button/>", {
                text: "",
                class: "btn btn-circle btn-warning buttonCancel",
                title: "Cancel",
                'data-toggle': 'tooltip',
            })
            buttonCancel.tooltip({
                animation: true,
            })
            buttonCancel.prepend(icon)
            return buttonCancel;
        }

        function generateSaveButton() {
            var icon = $("<span/>", {
                class: "glyphicon glyphicon-ok"
            })
            var buttonSave = $("<button/>", {
                text: "",
                class: "btn btn-circle btn-success buttonSave",
                title: "Save",
                'data-toggle': 'tooltip',
            })
            buttonSave.tooltip({
                animation: true,
            })
            buttonSave.prepend(icon)
            return buttonSave;
        }

        var entryID = entry.find(".glyphicon-edit").attr('id')
        var title = entry.find(".title");
        var titleOrig = title.html();
        var titleText = title.text();
        var tagRow = entry.find(".tagrow");
        var tagsDiv = entry.find(".tags");
        var tags = [];
        tagsDiv.children().each(function() {
            tags.push($(this).text());
        })
        var timestamp = entry.find(".timestamp");
        var newTagField = $('<input/>', {
            type: 'text',
            value: tags.join(","),
            id: "newTagField",
        })
        var tagRowOrig = tagRow.html()
        tagRow.html(newTagField)
        newTagField.tagit({
            singleField: true,
        })
        var buttonCancel = generateCancelButton();
        var buttonSave = generateSaveButton();
        tagRow.append(buttonCancel)
        tagRow.append(buttonSave)
        var newTitleField = $('<input/>', {
            type: 'text',
            class: 'edit-title',
            value: titleText,
        })
        title.html(newTitleField)
        buttonCancel.on('click', function() {
            title.html(titleOrig)
            tagRow.html(tagRowOrig);
            activateAllEditButtons();
        })
        buttonSave.on('click', function() {
            titleText = $(".edit-title").val()
            tags = $("#newTagField").val();
            data = {
                title: titleText,
                tags: tags,
                linkID: entryID
            }
            $.post("update.php", data)
                .done(function() {
                    loadLinks()
                })
        })
    }

    function addEntry() {
        var url = $("#url").val()
        var tags = $("#tagForm").tagit("assignedTags").join(",")

        var data = {
            url: url,
            tags: tags
        };
        $.post("add.php", data)
            .done(function(data) {
                $("#url").val("");
                $("#tagForm").tagit("removeAll")
                $(".addURLPanel").hide();
                loadLinks()
            })
    }

    $("#addEntryButton").click(function(e) {
        e.preventDefault()
        addEntry();
    })

    $("#addURl").on('submit', function(e) {
        e.preventDefault()
        addEntry();
    })

    $("#search").keypress(function(e) {
        if (e.which == 13) {
            e.preventDefault();
            if ($("#search").val().length > 0) {
                var query = $("#search").val();
                var post = {
                    'query': query,
                };
                $.post("search.php", post)
                    .done(function(data) {
                        fillEntries(data)
                        printAlert(query, $(".entries"))
                    })
            }
        }
    });


    $("#showPassword").click(function() {
        if ($("#showPassword").is(":checked")) {
            $("#password").attr('type', 'text');
        } else {
            $("#password").attr('type', 'password');
        }
    })






});
