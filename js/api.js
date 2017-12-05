function apiCall(module, action, post_data, func) {
    $.ajax({
        type: "POST",
        url: "modules/" + module + "/" + action + ".php",
        data: post_data,
        dataType: "json",
        success: function (data) {
            func(data);
        }
    }).fail(function (error) {
        alert(error);
    });
}

function generateComments(parent, location_id = 0, location_type = "GLOBAL") {
    getUiTemplate("comments", function (commentsTemplate) {
        const comments = $(commentsTemplate);
        parent.append(comments);
        getUiTemplate("comment", function (commentTemplate) {
            apiCall(
                "comments", "get",
                {
                    location_id: location_id,
                    location_type: location_type
                },
                function (data) {
                    $.each(data['data'], function (i, item) {
                        let comment = $(commentTemplate);
                        comment.find("#user").text(item['fullname']);
                        comment.find("#comment").text(item['comment']);
                        comments.append(comment);
                    });
                }
            )
        })
    })
}

function generatePost(parent, id) {
    getUiTemplate("post", function (postTemplate) {
        const post = $(postTemplate);
        parent.append(post);
        apiCall(
            "posts", "get",
            {
                id: id
            },
            function (data) {
                post.find('#')
                $.each(data['data'], function (i, item) {
                    post.find("#title").text(item['title']);
                    post.find("#user").text(item['fullname']);
                    post.find("#created").text(item['created']);
                    post.find("#content").text(item['content']);
                });
            }
        )
    })
}

function getUiTemplate(name, func) {
    jQuery.ajax({
        url: "ui/" + name + ".html",
        success: func
    });
}