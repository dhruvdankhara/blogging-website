
function likePost(postId) {
    $.ajax({
        type: "POST",
        url: "like.php",
        data: {
            post_id: postId
        },
        success: function (response) {
            $("#likeCount_" + postId).text(response);
            $("#icon_"+postId).find('i')
                .toggleClass('bi-hand-thumbs-up')
                .toggleClass('bi-hand-thumbs-up-fill');
        }
    });
}
