<?php
$data = [
    'id' => wf_isset_helper($_GET, 'id')
];

wf_load_admin_scripts($data, ['wf-select-2']);
?>
<div id="wf-app">
    <main-settings v-if="getPropertyByName('ajaxUrl')"></main-settings>
</div>
