/* *
 * @author Tuan Anh
 * @copyright 2009
 */
var total = 0;
function KeepCount( form, j, maxchoice, msg )
{

   var a = form['option_id[]'];
   total = 0;
   for( var i = 0; i < a.length; i ++ )
   {
      if( a[i].checked )
      {
         total = total + 1;

      }
      if( total > maxchoice )
      {
         alert( msg ) ;
         a[j].checked = false ;
         return false;
      }
   }

}

//  ---------------------------------------

function chkSelect( errsm )
{

   if ( total == 0 )
   {
      alert( errsm );
      return false;
   }
   return true;
}
