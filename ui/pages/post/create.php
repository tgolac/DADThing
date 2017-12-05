<form id="commentForm" name="comment" style="max-width: 600px; margin: auto; margin-top: 50px;">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title"/>
    </div>
    <div class="form-group">
        <label for="content">Content</label>
        <textarea class="form-control" id="content" name="content" rows="10"></textarea>
    </div>
    <div id="preview">

    </div>
    <button type="submit" name="submit" class="btn btn-primary">Comment</button>
</form>
<script src="js/preview.js"></script>