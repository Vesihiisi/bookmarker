$(document).ready(function() {

    $.get("tagList.php")
        .done(function(data) {
            $("#tag-column").html(data);
            $(".tag-list").stupidtable();
            $(".clickable").click(function() {
                location.href = $(this).find('td a').attr('href');
            })
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
