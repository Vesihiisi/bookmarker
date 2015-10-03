$(document).ready(function() {

    console.log("i'm working");
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
        $(this).children(".title-bar").children(".edit-links").css(
            "color", "#3A3A3A")
    }, function() {
        $(this).children(".title-bar").children(".edit-links").css(
            "color", "#EBEBEB");
    })

});
