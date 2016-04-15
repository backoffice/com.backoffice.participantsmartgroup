{* Extra template file to insert 'create smart group' checkbox in new event form *}

<div id="create-smart-group" style="display:none;">
   {$form.create_smartgroup.html} {$form.create_smartgroup.label} {help id="id-create_smartgroup" file="CRM/Event/Form/ManageEvent/EventInfo_extra.hlp"}
</div>
{* end Adding *}

{literal}
<script type="text/javascript">
CRM.$(function($) {
   if(!$('.crm-event-manage-eventinfo-form-block-create_smartgroup').length) {
       $('#EventInfo .crm-event-manage-eventinfo-form-block table tr:last').prev().after('<tr class="crm-event-manage-eventinfo-form-block-create_smartgroup"><td>&nbsp;</td><td>'+$('#create-smart-group').html()+'</td></tr>');
       $('#create-smart-group').remove();
     }else {
       $('#create-smart-group').remove();
     }
   });
</script>
{/literal}

