<!--Form with category and metro -->
<form id="lawyer-search-form">
    <input type="hidden" id="results-url" value="<?php echo esc_url($results_url); ?>">
    <div class="form-group form-group-1">
        <label for="lawyer-category">Buscar Categoría</label>
        <select name="lawyer-category" id="lawyer-category" class="select2">
            <option value="" disabled <?php echo empty($current_category_slug) ? 'selected' : ''; ?>>Buscar categoría</option>
            <optgroup label="Búsquedas Comunes">
                <?php
                $categories = get_terms(array('taxonomy' => 'lawyer-category', 'hide_empty' => false));
                foreach ($categories as $category) {
                    $selected = $current_category_slug === $category->slug ? 'selected' : '';
                    echo '<option value="' . esc_attr($category->slug) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                }
                ?>
            </optgroup>
        </select>
    </div>

    <div class="form-group">
        <label for="metro">Buscar Ciudad</label>
        <select name="metro" id="metro" class="select2" disabled>
            <option value="" disabled selected>Seleccione una categoría primero</option>
        </select>
    </div>

    <input type="submit" value="Buscar" disabled>
</form>


