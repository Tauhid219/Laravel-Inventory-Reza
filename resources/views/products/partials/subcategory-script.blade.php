@props([
    'selectedSubCategoryId' => null,
])

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const categorySelect = document.getElementById('category_id');
        const subCategorySelect = document.getElementById('sub_category_id');
        const selectedSubCategoryId = @json($selectedSubCategoryId);

        if (!categorySelect || !subCategorySelect) {
            return;
        }

        const placeholder = '<option selected disabled>Select a subcategory:</option>';

        const loadSubCategories = (categoryId) => {
            if (!categoryId) {
                subCategorySelect.innerHTML = placeholder;
                return;
            }

            subCategorySelect.innerHTML = '<option selected disabled>Loading...</option>';

            fetch(`/subcategories/${categoryId}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    return response.json();
                })
                .then((data) => {
                    subCategorySelect.innerHTML = placeholder;

                    data.subCategories.forEach((subCategory) => {
                        const option = document.createElement('option');
                        option.value = subCategory.id;
                        option.text = subCategory.name;

                        if (String(selectedSubCategoryId) === String(subCategory.id)) {
                            option.selected = true;
                        }

                        subCategorySelect.appendChild(option);
                    });
                })
                .catch(() => {
                    subCategorySelect.innerHTML = '<option selected disabled>Error loading subcategories</option>';
                });
        };

        categorySelect.addEventListener('change', function () {
            loadSubCategories(this.value);
        });

        if (categorySelect.value && subCategorySelect.options.length <= 1) {
            loadSubCategories(categorySelect.value);
        }
    });
</script>
