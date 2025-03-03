    // Kích hoạt dropdown submenu
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');
        dropdownSubmenus.forEach(function (submenu) {
            submenu.addEventListener('mouseenter', function () {
                const dropdown = submenu.querySelector('.dropdown-menu');
                if (dropdown) dropdown.style.display = 'block';
            });

            submenu.addEventListener('mouseleave', function () {
                const dropdown = submenu.querySelector('.dropdown-menu');
                if (dropdown) dropdown.style.display = 'none';
            });
        });
    });
    $(document).ready(function () {
        $('#search-input').autocomplete({
            source: function (request, response) {
                $('#search-input').addClass('loading');
                $.ajax({
                    url: searchSuggestionsUrl,
                    data: { query: request.term },
                    dataType: 'json',
                    success: function (data) {
                        response($.map(data, function (item) {
                            return {
                                label: item.product_name,
                                value: item.product_name,
                                id: item.id,
                                img: item.img,
                                price_formatted: item.price_formatted
                            };
                        }));
                    },
                    complete: function () {
                        $('#search-input').removeClass('loading');
                    }
                });
            },
            minLength: 2,
            select: function (event, ui) {
                window.location.href = `/product/${ui.item.id}`;
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
                .append(`
                    <div class="d-flex align-items-center p-2" style="border-bottom: 1px #ddd; width: 100%;">
                        <div class="row w-100">
                            <div class="col-2 d-flex align-items-center">
                                <img src="${item.img}" alt="${item.label}" class="rounded"
                                    style="width: 70px; height: 70px; object-fit: cover;">
                            </div>
                            <div class="col-10">
                                <div class="fw-bold text-dark">${item.label}</div>
                                <div class="text-danger fw-bold">${item.price_formatted}</div>
                            </div>
                        </div>
                    </div>
                `)
                .appendTo(ul);
        };
    });
    
    

    