document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded and parsed');
    const accordions = document.querySelectorAll('.branch-accordion .branch-header');
    console.log('Found', accordions.length, 'accordions');

    accordions.forEach((accordion, index) => {
        console.log('Attaching click listener to accordion', index);
        accordion.addEventListener('click', function () {
            console.log('Accordion', index, 'clicked');
            this.classList.toggle('active');
            const content = this.nextElementSibling;
            console.log('Content element:', content);
            const contentInner = content.querySelector('.branch-content-inner');
            console.log('Content inner element:', contentInner);

            if (content.style.maxHeight && content.style.maxHeight !== '0px') {
                console.log('Closing accordion');
                content.style.maxHeight = '0px';
            } else {
                console.log('Opening accordion. Inner content scrollHeight:', contentInner.scrollHeight);
                content.style.maxHeight = contentInner.scrollHeight + 'px';
            }
        });
    });

    const searchInput = document.getElementById('branch-search-input');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            const provinceGroups = document.querySelectorAll('.province-group');
            let totalVisibleBranches = 0;

            provinceGroups.forEach(group => {
                const accordions = group.querySelectorAll('.branch-accordion');
                let hasVisibleBranches = false;

                accordions.forEach(accordion => {
                    const title = accordion.querySelector('.branch-header').textContent.toLowerCase();
                    const content = accordion.querySelector('.branch-content-inner').textContent.toLowerCase();
                    if (title.includes(filter) || content.includes(filter)) {
                        accordion.style.display = "";
                        hasVisibleBranches = true;
                        totalVisibleBranches++;
                    } else {
                        accordion.style.display = "none";
                    }
                });

                if (hasVisibleBranches) {
                    group.style.display = "";
                } else {
                    group.style.display = "none";
                }
            });

            const noResultsMessage = document.getElementById('no-results-message');
            if (totalVisibleBranches === 0) {
                noResultsMessage.style.display = "block";
            } else {
                noResultsMessage.style.display = "none";
            }

            const paginationContainer = document.getElementById('pagination-container');
            if (filter) {
                paginationContainer.style.display = "none";
            } else {
                paginationContainer.style.display = "";
                setupPagination(10); // 10 items per page
            }
        });
    }

    function setupPagination(itemsPerPage) {
        const accordions = document.querySelectorAll('.branch-accordion');
        const paginationContainer = document.getElementById('pagination-container');
        const numPages = Math.ceil(accordions.length / itemsPerPage);

        paginationContainer.innerHTML = '';

        for (let i = 1; i <= numPages; i++) {
            const pageLink = document.createElement('a');
            pageLink.href = '#';
            pageLink.innerText = i;
            pageLink.addEventListener('click', function(e) {
                e.preventDefault();
                showPage(i, itemsPerPage);
            });
            paginationContainer.appendChild(pageLink);
        }

        showPage(1, itemsPerPage);
    }

    function showPage(pageNum, itemsPerPage) {
        const accordions = document.querySelectorAll('.branch-accordion');
        const startIndex = (pageNum - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;

        accordions.forEach((accordion, index) => {
            if (index >= startIndex && index < endIndex) {
                accordion.style.display = '';
            } else {
                accordion.style.display = 'none';
            }
        });
    }

    const provinceSelect = document.getElementById('province-select');
    if (provinceSelect) {
        provinceSelect.addEventListener('change', function() {
            const selectedProvince = this.value;
            if (selectedProvince) {
                window.location.href = window.location.pathname + '?province=' + selectedProvince;
            } else {
                window.location.href = window.location.pathname;
            }
        });
    }

    window.addEventListener('resize', () => {
        const activeContents = document.querySelectorAll('.branch-content');
        activeContents.forEach(content => {
            if (content.style.maxHeight && content.style.maxHeight !== '0px') {
                const contentInner = content.querySelector('.branch-content-inner');
                content.style.maxHeight = contentInner.scrollHeight + 'px';
            }
        });
    });
});
