{include file='sites/all/rating/style.php'}

<form action=''>
  <table>
    

    <tr>
      <td colspan='1'>
        <input type='number' min="0" max='20' class='message'  placeholder='Место' name='position' value='1' />
        <input type='text' class='message'  placeholder='Фамилия Имя' name='name_get' required="required" autocomplete="off" />
        <input type='text' class='message'  placeholder='Год рожд.' name='age' required="required" autocomplete="off" />
        <br />
        <label><input type='radio'  name='gender' value=1 
        {if $c_gender eq 1} checked="checked" {/if} 
        /> Женщина</label>
        <label><input type='radio'  name='gender' value=2 
        {if $c_gender eq 2} checked="checked" {/if} 
        /> Мужчина</label>
      </td>
    </tr>
    
    
    <tr>
      <td>
      <input type='hidden' name='athlete_id' />
      <textarea id='textarea' class='textbox textarea'> </textarea>
      </td>
    </tr>
    
    
    <tr>
    	<td>
        <select name="country">
        <option value="{$c_country_iso}">{$c_country_name}</option>
        {foreach from=$country item=v}
          <option value="{$v.iso}">{$v.abbreviated_name}</option>
        {/foreach}
    		</select>
      </td>
    </tr>
    
    <tr>
      <td>
        <input type='text' class='message'  placeholder='Город' name='city' />
        <br />
        <select name='subject_rf' data-placeholder="Выбрать субъект РФ..." class="chosen-select" >
          <option value=""></option>
            {foreach from=$option_rf item=v}
              <option>{$v}</option>
            {/foreach}
        </select>
      </td>
    </tr>
  
     <tr>
      <td> 
        <select name="competition">
          {if isset($c_competition)} 
            <option value='{$c_competition}'>{$name_comp}</option> 
          {/if}
          <option>Выбрать название соревнований</option>
          {foreach from=$competition_list item=v}
            <option value="{$v.id}">{$v.name_ru}</option>
          {/foreach}
    		</select>
        <select name="age_category">
          <option value='{$age_category_c_id}'>{$age_category_name}</option> 
          {foreach from=$age_category_list item=v}
            <option value="{$v.id}">{$v.reduction}</option>
          {/foreach}
    		</select>
        <input type='number' min='1960' max='2115' class='message'  placeholder='Год соревнованй'
          name='date' value="{$c_date}" required="required"  autocomplete="off" />
        <br /> 
        <label>
          <input type='radio' name='devizion'
          {if $c_devizion eq 1} checked="checked" {/if}  value='1' /> Классический
        </label>
        <label>
          <input type='radio' name='devizion'
          {if $c_devizion eq 2} checked="checked" {/if}  value='2' /> Экипировка
        </label>
      </td>
    </tr>
   
    <tr>
      <td>
        <input type='text' required="required" autocomplete="off" class='message'  placeholder='Вес' name='weight' size='6' />
        <input type='text' required="required" autocomplete="off" class='message'  placeholder='Присед' name='squat' size='6' />
        <input type='text' required="required" autocomplete="off" class='message'  placeholder='Жим' name='brench' size='6' />
        <input type='text' required="required" autocomplete="off" class='message'  placeholder='Тяга' name='deadlift' size='6' />
        <input type='text' required="required" autocomplete="off" class='message'  placeholder='Сумма' name='total' size='6' />
        <input type='text' required="required" autocomplete="off" class='message'  placeholder='Вилкс' name='wilks' size='8' />
      </td>
    </tr>
    <tr>
      <td>
        <input type='text'  autocomplete="off" class='message'  placeholder='Тренер' name='trainer' />
      </td>
    </tr>
    <tr>
      <td>
        <input type='submit' class="button" name='doAdd'>
      </td>
    </tr>
  </table>
</form>

<table cellpadding="2" border="1" width='100%' style='font-size: xx-small;'>
{foreach from=$NewRow item=v}
  	<tr>
  		<td><b style='color: red'>{$v.gender}</b></td>
  		<td><a href='/node/2?athlete_id={$v.athlete_id}&amp;competition_id={$v.competitionid}'>{$v.name}</a></td>
  		<td>{$v.age}</td>
  		<td>{$v.country}</td>
  		<td>{$v.fo}</td>
  		<td>{$v.subject_rf}</td>
  		<td>{$v.city}</td>
  		<td>{$v.translate_name}</td>
  		<td>{$v.competition}</td>
      <td><b>{$v.devizion}</b></td>
  		<td>{$v.date}</td>      
  		<td><b style='color: red'>{$v.age_category}</b></td>
  		<td><b>{$v.position}</b></td>
  		<td>{$v.weight}</td>
  		<td>{$v.squat}</td>
  		<td>{$v.brench}</td>
  		<td>{$v.deadlift}</td>
  		<td>{$v.total}</td>
  		<td>{$v.wilks}</td>
  		<td>{$v.trainer}</td>
  	</tr> 
{/foreach}
</table>



 
