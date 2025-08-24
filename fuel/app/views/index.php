<ul data-bind="foreach: all_actions">
    <li>
        <span data-bind="text: name"></span>
        (<span data-bind="text: frequency"></span>)
    </li>
</ul>


  
<script>
    function ActionsViewModel(actions_data){
        this.all_actions = ko.observableArray(actions_data);
        
    }

</script>
