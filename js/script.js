$(document).ready(function() {

    function getUrlVars() {
        var vars = [],
            hash;
        var q = document.URL.split('?')[1];
        if (q != undefined) {
            q = q.split('&');
            for (var i = 0; i < q.length; i++) {
                hash = q[i].split('=');
                vars.push(hash[1]);
                vars[hash[0]] = hash[1];
            }
        }
        return vars
    }


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


    $.get("tagList.php")
        .done(function(data) {
            $("#tag-column").html(data);
            $(".tag-list").stupidtable();
            $(".clickable").click(function() {
                location.href = $(this).find('td a').attr('href');
            })
            var tableRow = $("td").filter(function() {
                return $(this).text() == getUrlVars()["tag"];
            }).closest("tr");
            tableRow.addClass("highlightedTag");
            $(".tag-list").sticky({
                topSpacing: 10
            });
        })

    function fillEntries(data) {
        $(".entries").html(data);
        addFunctionsToEntryInterface();
    }

    var urlParams = getUrlVars();

    if (urlParams.length == 0) {
        $(".entries").html("loading.......")
        $.get("allLinks.php")
            .done(function(data) {
                fillEntries(data);
            })
    } else {
        var tag = urlParams["tag"];
        $.get("tagLinks.php", {
                tag: tag
            })
            .done(function(data) {
                fillEntries(data);
                printAlert(tag, $(".entries"))
            })
    }





    $("#tagForm").tagit({
        singleField: true,
    });

    $("#showPanel").click(function() {
        console.log("click");
        $(".addURLPanel").slideToggle();
    })



    function addFunctionsToEntryInterface() {
        $(".glyphicon-remove").tooltip({
            animation: true,
        });

        $(".glyphicon-edit").tooltip({
            animation: true,
        });

        $(".entry").hover(function() {
            $(this).children(".edit-links").css(
                "color", "#3A3A3A")
        }, function() {
            $(this).children(".edit-links").css(
                "color", "#EBEBEB");
        })

        $(".glyphicon-remove").on('click', function() {
            var parent = $(this).parents(".entry");
            console.log("click")
            if (confirm("Are you sure?")) {
                var linkID = this.id;
                $.post("delete.php", {
                        linkID: linkID
                    })
                    .done(function() {
                        parent.slideUp()
                    })
            }
        })

        $(".glyphicon-edit").on('click', function() {
            var buttonSave = $("<button/>", {
                text: "Save",
                class: "btn btn-success"
            })
            var buttonCancel = $("<button/>", {
                text: "Cancel",
                class: "btn btn-warning"
            })
            var entry = $(this).parents(".entry");
            var tagRow = entry.find(".tagrow")
            var tagsDiv = entry.find(".tags")
            var tags = []
            tagsDiv.children().children().each(function(){
                tags.push($(this).text())
            })
            var timestamp = entry.find(".timestamp");
            var newTagField = $('<input/>', {
                type: 'text',
                value : tags.join(","),

            })

            tagRow.html(newTagField)
            newTagField.tagit({
                singleField: true,
            })
            tagRow.append(buttonCancel)
            tagRow.append(buttonSave)

        })
    }









});
