<?php

class Specification {
    function __construct($specification_id, $specification_name, $specification_type, $description, $functional_areas, $attribute_4_name, $attribute_4_value, $last_revised, $ratio = null, $attribute_7_name = 'public', $attribute_7_value = null) {
        $this->specification_id = $specification_id;
        $this->specification_name = $specification_name;
        $this->specification_type = $specification_type;
        $this->description_val = $description;
        $this->functional_areas = $functional_areas;
        $this->attribute_4_name = $attribute_4_name;
        $this->attribute_4_value = $attribute_4_value;
        $this->last_revised = $last_revised;
        $this->ratio = $ratio;
        $this->attribute_7_name = $attribute_7_name;
        $this->attribute_7_value = $attribute_7_value;
    }
}
?>
