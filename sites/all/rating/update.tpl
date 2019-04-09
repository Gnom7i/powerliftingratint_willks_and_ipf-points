<style type="text/css">
table td{ 
  vertical-align: top
}
</style>  
<form action="">
<table>
	<tr>
		<td>  
  <fieldset><legend>Данные спортсмена id: {$update.athlete_id}</legend>
  <input type="hidden" name='athlete_id' value='{$update.athlete_id}' />
  <input type="hidden" name='competition_id' value='{$update.competition_row_id}' />
  <input type="hidden" name='PathHistory' value='{$PathHistory}' />
	<label>Имя</label>
  <input type="" name='name' value='{$update.name}' /> <br></br>
	<label>Пол</label>
  <input type="number" min='1' max='2' name='gender' value='{$update.gender}' /> 
    <small>1 - Ж, 2 - М</small><br></br>
  <label>Год рожд.</label>
	<input type="number" name='age' min='1900' max='2115' value='{$update.age}' size='4' /> <br></br>
  <label>Страна</label>
  <select name='country'>
    <option value='{$update.iso}'>{$update.country}</option>
    {foreach from=$CountrySelect item=v}
      <option value='{$v.IsoList}'>{$v.CountryList}</option>
    {/foreach}
  </select> 
  <br></br>
  <label>Федеральный округ РФ</label>
  <select name='fo'>
    <option value='{$update.fo}'>{$update.fo}</option>
    {foreach $FoSelect as $v}
      <option value='{$v.FoList}'>{$v.FoList}</option>
    {/foreach}
  </select>
  <br></br>
  <label>Субъект</label>
	<input type="" name='subject_rf' value='{$update.subject_rf}' /> <br></br>
  <label>Город</label>
	<input type="" name='city' value='{$update.city}' /> <br></br>
  </fieldset>
    </td>
		<td>
    <fieldset>
    <legend>
      Запись строки id: {$update.competition_row_id} таблицы COMPETITION.<br />Организация соревнований
    </legend>
  <label>Название</label>
  <select name='competition'>
  <option value='{$update.competitionid}'>{$update.competitionname}</option>
  {foreach $CompetitionSelect as $v}
      <option value='{$v.CompetitionId}'>{$v.CompetitionList}</option>
  {/foreach}
  </select>
	<br></br>
  <label>Девизион</label>
  <input type="number" min='1' max='2' name='devizion' value='{$update.devizion}' /> 
    <small>1 - БЭ, 2 - ЭК.</small><br></br>
  <label>Возрастная категория</label>
  <select name='age_category'>
    <option value='{$update.age_cat_id}'>{$update.age_cat}</option>
    {foreach $AgeCategorySelect as $v}
      <option value='{$v.AgeCategoryId}'>{$v.AgeCategoryList}</option>
    {/foreach}
  </select>
  <br></br>
  <label>Год проведения</label>
	<input type="number"  min='1960' max='2115' name='date' value='{$update.date}' /> <br></br>
  </fieldset>
    </td>
  <td>
  <fieldset>
  <legend>
    Запись строки id: {$update.competition_row_id} таблицы COMPETITION.<br />Результат на соревновании
  </legend>
  <label>Занятое место</label>
	<input type="number" min='0' max='20' name='position' value='{$update.position}' /> <br/></br>
  <label>Вес</label>
	<input type="" size='6' name='weight' value='{$update.weight}' /> <br></br>
  <label>Присед</label>
	<input type="" size='6' name='squat' value='{$update.squat}' /> <br></br>
  <label>Жим</label>
	<input type="" size='6' name='brench' value='{$update.brench}' /> <br></br>
  <label>Тяга</label>
	<input type="" size='6' name='deadlift' value='{$update.deadlift}' /> <br></br>
  <label>Сумма</label>
	<input type="" size='6' name='total' value='{$update.total}' /> <br></br>
  <label>Вилкс</label>
	<input type="" size='6' name='wilks' value='{$update.wilks}' /> <br></br>
  <label>Тренер</label>
	<input type="" name='trainer' value='{$update.trainer}' /> <br></br>
  </fieldset>
  </td>
	</tr>
</table>
<input type='submit' name='update' value='Обновить' />
  
  
</form>