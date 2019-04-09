<meta charset="utf-8" />
<style>
.tabstyle1 {
      border: 5px ridge #73766f; 
      border-collapse: collapse;
      font-size: x-small;
}               
 
.tabstyle1 thead,  th {
      background: -webkit-linear-gradient(top right, #b8b9b1, #fff); /* для webkit-браузеров */
      background: -moz-linear-gradient(top right, #b8b9b1, #fff); /* для firefox 3.6+ */
      background: -o-linear-gradient(top right, #b8b9b1, #fff); /* для Opera 11.10+ */
      background: -ms-linear-gradient(top right, #b8b9b1, #fff); /* для IE10+ */
      text-align: center; 
      vertical-align: text-top;
      padding:10px;
      border-bottom: 3px ridge #73766f;
      border-left: 1px solid #73766f; 
      border-collapse: collapse;
} 

.tabstyle1 td, tr {
      text-align:left;
      vertical-align: text-top;
      padding:10px;
      border: 1px solid #73766f;
      border-collapse: collapse;
      background: #fff;
}


/* Базовые стили формы */
form{
  margin:0 auto;
  max-width:95%;
  box-sizing:border-box;
  padding:40px;
  border-radius:5px; 
  background:RGBA(255,255,255,1);
  -webkit-box-shadow:  0px 0px 15px 0px rgba(0, 0, 0, .45);        
  box-shadow:  0px 0px 15px 0px rgba(0, 0, 0, .45);  
}
/* Стили полей ввода */
.textbox{
  height:50px;
  width:100%;
  border-radius:3px;
  border:rgba(0,0,0,.3) 2px solid;
  box-sizing:border-box;
  font-family: 'Open Sans', sans-serif;
  font-size:18px; 
  padding:10px;
  margin-bottom:30px;  
}
.message:focus,
.textbox:focus{
  outline:none;
   border:rgba(24,149,215,1) 2px solid;
   color:rgba(24,149,215,1);
}
/* Стили текстового поля */
.message{
    background: rgba(255, 255, 255, 0.4); 
    
    border:rgba(0,0,0,.3) 2px solid;
    box-sizing:border-box;
    -moz-border-radius: 3px;
    font-size:18px;
    font-family: 'Open Sans', sans-serif;
    -webkit-border-radius: 3px;
    border-radius: 3px; 
   
    padding:10px;
    margin-bottom:30px;
    overflow:hidden;
}
/* Базовые стили кнопки */
.button{ 
  border-radius:3px;
  border:rgba(0,0,0,.3) 0px solid;
  box-sizing:border-box;
  padding:10px;
  background:#90c843;
  color:#FFF;
  font-family: 'Open Sans', sans-serif;  
  font-weight:400;
  font-size: 16pt;
  transition:background .4s;
  cursor:pointer;
}
/* Изменение фона кнопки при наведении */
.button:hover{
  background:#80b438;
}
</style>