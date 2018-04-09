<?php

class Specification {
    function __construct($specification_id, $specification_name, $specification_type, $description, $functional_areas, $attribute_4_name, $attribute_4_value, $last_revised) {
        $this->specification_id = $specification_id;
        $this->specification_name = $specification_name;
        $this->specification_type = $specification_type;
        $this->description_val = $description;
        $this->functional_areas = $functional_areas;
        $this->attribute_4_name = $attribute_4_name;
        $this->attribute_4_value = $attribute_4_value;
        $this->last_revised = $last_revised;
    }
    // to_html() 
    // fetch_related_specifications()
}
?>
