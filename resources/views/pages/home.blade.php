<?php
// ⚡ home

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Home - TokoPun')] class extends Component {};

?>

<div class="">
    <livewire:pages.service-selection />
    <livewire:pages.description />
    <livewire:pages.catalog />
</div>
