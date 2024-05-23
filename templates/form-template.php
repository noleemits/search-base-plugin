<form id="lawyer-search-form">
    <div class="form-group">
        <label for="lawyer-category">Buscar Categoría</label>
        <select name="lawyer-category" id="lawyer-category" class="select2">
            <option value="" disabled selected>Buscar categoría</option>
            <optgroup label="Búsquedas Comunes">
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
        <label for="metro">Buscar Metro</label>
        <select name="metro" id="metro" class="select2" disabled>
            <option value="" disabled selected>Seleccione una categoría primero</option>
        </select>
    </div>

    <input type="submit" value="Buscar" disabled>
</form>
