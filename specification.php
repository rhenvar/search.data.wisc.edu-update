<?php

class Specification {
    function __construct($specification_id, $specification_version_id, $specification_name, $specification_type, $description, $functional_areas, $attribute_4_name, $attribute_4_value, $last_revised, $ratio = null, $attribute_7_name = 'public', $attribute_7_value = null, $attribute_8_name = 'Request URL', $attribute_8_value = null, $additional_details = "") {
        $this->specification_id = $specification_id;
	$this->specification_version_id = $specification_version_id;
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
        $this->attribute_8_name = $attribute_8_name;
        $this->attribute_8_value = $attribute_8_value;
	$this->additional_details = $additional_details;
    }
}
?>
