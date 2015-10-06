$(document).ready(function() {
    function getUrlVars() {
        var vars = [],
            hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
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
        })

    $(".tag-list").sticky({
        topSpacing: 10
    });

    $("#tagForm").tagit({
        singleField: true,
    });

    $("#showPanel").click(function() {
        console.log("click");
        $(".addURLPanel").slideToggle();
    })

    $(".entry").hover(function() {
        $(this).children(".edit-links").css(
            "color", "#3A3A3A")
    }, function() {
        $(this).children(".edit-links").css(
            "color", "#EBEBEB");
    })

    $(".glyphicon-remove").tooltip({
        animation: true,
    });

    $(".glyphicon-edit").tooltip({
        animation: true,
    });

    $(".glyphicon-remove").on('click', function() {
        var parent = $(this).parents(".entry");
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









});
