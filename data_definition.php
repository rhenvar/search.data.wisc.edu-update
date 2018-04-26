<?php

class DataDefinition {
    function __construct($definition_id, $definition_name, $definition_type, $functional_definition, $functional_areas, $has_reports, $ratio = null) {
        $this->definition_id = $definition_id;
        $this->definition_name = $definition_name;
        $this->definition_type = $definition_type;
        $this->functional_definition = $functional_definition;
        $this->functional_areas = $functional_areas;
        $this->has_reports = $has_reports;
        $this->ratio = $ratio;
    }
}
?>
