<form id="lawyer-search-form">
    <div class="form-group">
        <label for="lawyer-category">Search Category</label>
        <select name="lawyer-category" id="lawyer-category" class="select2">
            <option value="" disabled selected>Search category</option>
            <optgroup label="Common Searches">
                <?php
                $categories = get_terms(array('taxonomy' => 'lawyer-category', 'hide_empty' => false));
                foreach ($categories as $category) {
                    echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                }
                ?>
            </optgroup>
        </select>
    </div>

    <div class="form-group">
        <label for="metro">Search Metro</label>
        <select name="metro" id="metro" class="select2" disabled>
            <option value="" disabled selected>Please select a category first</option>
        </select>
    </div>

    <input type="submit" value="Search" disabled>
</form>
