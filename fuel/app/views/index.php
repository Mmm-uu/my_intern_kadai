<ul data-bind="foreach: all_actions" class="actions-list">
    <li>
        <span data-bind="text: name"></span>
        <!-- <span class="frequency">(<span data-bind="text: frequency"></span>)</span> -->
        <button data-bind="click: $parent.onMove">></button>
        <button data-bind="click: $parent.onDelete">×</button>
    </li>
</ul>

<style>
    .actions-list li {
        margin-left: -25px;
        /* display: flex; */
        align-items: center;
        gap: 8px; /* ボタンと文字の間隔 */
    }

    .actions-list button {
        padding: 2px 6px;
        font-size: 10px;
        cursor: pointer;
    }
    /*
    .actions-list li {
        margin-left: -30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .frequency { 
        font-size: 0.8em;
        color: #555;
    }
    */

</style>
  
<script>
    function ActionsViewModel(actions_data){
        this.all_actions = actions_data;

        

        
    }

</script>
