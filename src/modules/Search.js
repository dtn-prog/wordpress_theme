import $ from 'jquery';

class Search {
    constructor() {
        this.addSearchHTML();
        this.reSultDiv = $("#search-overlay__results");
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.searchField = $("#search-term");
        this.events();
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.typingTimer;
        this.previousValue;
    }

    //events
    events() {
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));

        $(document).on("keydown", this.keyPressDispatcher.bind(this));

        this.searchField.on('keyup', this.typingLogic.bind(this));

    }

    // methods
    typingLogic() {
        if (this.searchField.val() != this.previousValue) {
            clearTimeout(this.typingTimer);

            if (this.searchField.val()) {
                if (!this.isSpinnerVisible) {
                    this.reSultDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }

                this.typingTimer = setTimeout(this.getResults.bind(this), 750);
            } else {
                this.reSultDiv.html('');
                this.isSpinnerVisible = false;
            }

        }

        this.previousValue = this.searchField.val();
    }

    getResults() {
        let search = this.searchField.val();
        let query = `${universityData.root_url}/wp-json/university/v1/search?term=${search}`;
        $.getJSON(query, (results) => {
            this.reSultDiv.html(`
                <div class="row">
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">general info</h2>
                        ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>no results</p>'}
                        ${results.generalInfo.map((item) => `<li><a href="${item.permalink}">${item.title}</a> ${item.postType == 'post' ? `by ${item.authorName}` : ''} </li>`).join('')}
                        ${results.generalInfo.length ? '</ul>' : ''}
                    </div>
    
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Programs</h2>
                        ${results.programs.length ? '<ul class="link-list min-list">' : `<p>no results</p> <a href="${universityData.root_url}/programs">View All Programs</a>`}
                        ${results.programs.map((item) => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
                        ${results.programs.length ? '</ul>' : ''}
                    </div>
    
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Professors</h2>
                        ${results.professors.length ? '<ul class="professor-cards">' : `<p>no results</p>`}
                        ${results.professors.map((item) => `
                        <li class="professor-card__list-item">
                        <a class="professor-card" href="${item.permalink}">
                            <img src="${item.image}" alt="" class="professor-card__image">
                            <span class="professor-card__name">${item.title}</span>
                        </a>
                    </li>
                        `).join('')}
                        ${results.professors.length ? '</ul>' : ''}
                    </div>
    
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Event</h2>
                        ${results.events.length ? '' : `<p>no results</p> <a href="${universityData.root_url}/events">View All Events</a>`}
                        ${results.events.map((item) => `
                        <div class="event-summary">
                        <a class="event-summary__date t-center" href="${item.permalink}">
                        <span class="event-summary__month">${item.month}</span>
                        <span class="event-summary__day">${item.day}</span>  
                        </a>
                        <div class="event-summary__content">
                            <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                            <p>${item.description} <a href="${item.permalink}" class="nu gray">Learn more</a></p>
                        </div>
                    </div>
                        `).join('')}
                    </div>
                </div>
            `);

            this.isSpinnerVisible = false;
        });
    }

    keyPressDispatcher(e) {

        //s = 83
        if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')) {
            this.openOverlay();
        }

        //esc = 27
        if (e.keyCode == 27 && this.isOverlayOpen) {
            this.closeOverlay();
        }

    }

    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
        this.searchField.val("");
        setTimeout(() => {
            this.searchField.trigger("focus");
        }, 400);
        this.isOverlayOpen = true;

        return false;
    }

    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        this.isOverlayOpen = false;
    }

    addSearchHTML() {
        $("body").append(`<div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
            <input type="text" name="" id="search-term" class="search-term" placeholder="search?">
            <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
          </div>
        </div>
    
        <div class="container">
          <div id="search-overlay__results"></div>
        </div>
    </div>`);
    }
}

export default Search;