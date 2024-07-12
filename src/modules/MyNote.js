import $ from 'jquery';

class MyNote {
    constructor() {
        this.events();
    }

    events() {
        $("#my-notes").on('click', ".delete-note", this.deleteNote.bind(this))
        $("#my-notes").on('click', ".edit-note", this.editNote.bind(this))
        $("#my-notes").on('click', ".update-note", this.updateNote.bind(this))
        $(".submit-note").on('click', this.createNote.bind(this))
    }

    createNote(e) {


        let noteTitle = $(".new-note-title");
        let noteContent = $(".new-note-body");
        let newPost = {
            'title': noteTitle.val(),
            'content': noteContent.val(),
            'status': 'publish',

        };

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: `${universityData.root_url}/wp-json/wp/v2/note/`,
            type: 'POST',
            data: newPost,
            success: (response) => {
                noteTitle.val("");
                noteContent.val("");

                $(`
                <li data-id="${response.id}">
                <input readonly class="note-title-field" type="text" value="${response.title.raw}">
                <span span class= "edit-note" > <i class="fa fa-pencil"></i> Edit</span >
                <span class="delete-note"><i class="fa fa-trash-o"></i> Delete</span>
                <textarea readonly class="note-body-field">${response.content.raw}</textarea>

                <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right"></i> Save</span>
                </li >
                    `).prependTo("#my-notes").hide().slideDown();

                console.log('success');
                console.log(response);
            },
            error: (reponse) => {
                //responseText: "you have reached your note limit"
                if (reponse.responseText == "you have reached your note limit") {
                    $(".note-limit-message").addClass("active");
                }
                console.log('fail');
                console.log(reponse);
            },
        });
    }

    editNote(e) {
        let thisNote = $(e.target).parent("li");
        if (thisNote.data("state") === "editable") {
            this.makeNoteReadOnly(thisNote);
        } else {
            this.makeNoteEditable(thisNote);
        }
    }

    makeNoteEditable(thisNote) {
        thisNote.find(".edit-note").html(`<i i class= "fa fa-times" ></i > Cancel`);
        thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
        thisNote.find(".update-note").addClass("update-note--visible");

        thisNote.data("state", "editable");
    }

    makeNoteReadOnly(thisNote) {
        thisNote.find(".edit-note").html(`<i i class= "fa fa-pencil" ></i > Edit`);
        thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
        thisNote.find(".update-note").removeClass("update-note--visible");

        thisNote.data("state", "cancel");
    }

    deleteNote(e) {
        let thisNote = $(e.target).parent("li");

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: `${universityData.root_url}/wp-json/wp/v2/note/${thisNote.data('id')}`,
            type: 'DELETE',
            success: (response) => {
                thisNote.slideUp();
                console.log('success');
                console.log(response);
                if (response.userNoteCount < 4) {
                    $(".note-limit-message").removeClass("active");
                }
            },
            error: (response) => {
                console.log(response);
            },
        });
    }

    updateNote(e) {
        let thisNote = $(e.target).parent("li");
        let updatePost = {
            'title': thisNote.find(".note-title-field").val(),
            'content': thisNote.find(".note-body-field").val()
        };

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: `${universityData.root_url}/wp-json/wp/v2/note/${thisNote.data('id')}`,
            type: 'POST',
            data: updatePost,
            success: (response) => {
                this.makeNoteReadOnly(thisNote);

                console.log('success');
                console.log(response);
            },
            error: (xhr, status, error) => {
                alert(`fail to delete : ${error}`);
                console.log(error);
            },
        });
    }

}

export default MyNote;