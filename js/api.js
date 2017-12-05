function apiCall(module, action, post_data, func) {
    $.ajax({
        type: "POST",
        url: "modules/" + module + "/" + action + ".php",
        data: post_data,
        dataType: "json",
        success: function (data) {
            if (data['status'] === 1) {
                func(data);
            } else if (data['status'] === -3) {
                window.location.href = "/?loc=LOGIN";
            }
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
        apiCall(
            "posts", "get",
            {
                id: id
            },
            function (data) {
                const post = $(postTemplate);
                let postData = data['data'];
                post.find('#title').text(postData['title']);
                post.find('#user').text(postData['fullname']);
                post.find('#created').text(postData['created']);
                post.find('#content').text(postData['content']);
                post.find('#location_id').attr('value', postData['id']);
                post.find('#location_type').attr('value', 'POST');
                post.find('#commentForm').submit(commentFormHandler);
                parent.append(post);
                generateComments(post.find("#comments"), postData['id'], 'POST')
            }
        )
    })
}

function createComment(parent, data) {
    apiCall("comments", "create", data,
        function (data) {
            parent.find('#comments').empty();
            generateComments(parent.find("#comments"), data['location_id'], data['location_type'])
        }
    );
}

function getUiTemplate(name, func) {
    jQuery.ajax({
        url: "ui/" + name + ".html",
        success: func
    });
}

function commentFormHandler(e) {
    e.preventDefault();
    createComment($(this).parent(), objectify(this));
}