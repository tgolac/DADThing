const ERROR_SUCCESS = 1;
const ERROR_FIELDS = -1;
const ERROR_SYSTEM = -2;
const ERROR_LOGIN = -3;
const ERROR_ALERT = -4;
const MAX_MINI_POST_LENGTH = 1000;

function apiCall(module, action, post_data, func, func2) {
    func2 = func2 || function (data) {

        };
    $.ajax({
        type: "POST",
        url: "modules/" + module + "/" + action + ".php",
        data: post_data,
        dataType: "json",
        success: function (data) {
            if (data['status'] === ERROR_SUCCESS) {
                func(data);
            } else if (data['status'] === ERROR_LOGIN) {
                window.location.href = "?page=login";
            } else {
                func2(data);
            }
        }
    }).fail(function (error) {
        console.log(error);
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
                        comment.find("#created").text(item['created']);
                        comments.prepend(comment);
                    });
                }
            )
        })
    })
}

function generatePosts(parent, start = 0, limit = 10) {
    getUiTemplate("miniPost", function (postTemplate) {
        apiCall(
            "posts", "get",
            {
                start: start,
                limit: limit
            },
            function (data) {
                $.each(data['data'], function (i, postData) {
                    const post = $(postTemplate);
                    post.find('#title').text(postData['title']);
                    post.find('#user').text(postData['fullname']);
                    post.find('#created').text(postData['created']);
                    post.find('#content').text($.trim(postData['content']).substring(0, MAX_MINI_POST_LENGTH).trim() + "...");
                    post.find('#read_more').attr('href', post.find('#read_more').attr('href').replace('{id}', postData['id']));
                    parent.append(post);
                })
            }
        )
    });
}

function generatePost(parent, id) {
    getUiTemplate("singlePost", function (postTemplate) {
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

function createComment(parent, input_data) {
    apiCall("comments", "create", input_data,
        function (data) {
            parent.find('#comments').empty();
            generateComments(parent.find("#comments"), input_data['location_id'], input_data['location_type'])
        }
    );
}

function getUiTemplate(name, func) {
    jQuery.ajax({
        url: "ui/components/" + name + ".html",
        success: func
    });
}

function commentFormHandler(e) {
    e.preventDefault();
    createComment($(this).parent(), objectify(this));
}