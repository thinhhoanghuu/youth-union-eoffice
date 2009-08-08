function fetch_object( idname )
{
   if ( document.getElementById )
   {
      return document.getElementById( idname );
   }
   else if ( document.all )
   {
      return document.all[idname];
   }
   else if ( document.layers )
   {
      return document.layers[idname];
   }
   else
   {
      return null;
   }
}

//  ---------------------------------------

function CheckAllCheckbox( f, checkboxname )
{
   var len = f.elements.length;
   for( var i = 0; i < len; i ++ )
   {
      if( f.elements[i].name == checkboxname )
      {
         f.elements[i].checked = true;
      }
   }
   return;
}

//  ---------------------------------------

function UnCheckAllCheckbox( f, checkboxname )
{
   var len = f.elements.length;
   for( var i = 0; i < len; i ++ )
   {
      if( f.elements[i].name == checkboxname )
      {
         f.elements[i].checked = false;
      }
   }
   return;
}

//  ---------------------------------------

function LTrim( Str )
{
   return Str.replace( /^\s+/, '' );
}

//  ---------------------------------------

function RTrim( Str )
{
   return Str.replace( /\s+$/, '' );
}

//  ---------------------------------------

function Trim( Str )
{
   return RTrim( LTrim( Str ) );
}

//  ---------------------------------------

function isEmpty( Str )
{
   empty = ( Str === "" ) ? true :  false;
   return empty;
}

//  ---------------------------------------

function isNumber( Digit )
{
   return / ^ \d + [\.\d * ] ? $ / .test( Digit );
}

//  ---------------------------------------

function isAlphabet( Digit )
{
   return / ^ [a - zA - Z]$ / .test( Digit );
}

//  ---------------------------------------

function isInteger( Str )
{
   return / ^ [ + - ] ? \d + $ / .test( Str );
}

//  ---------------------------------------

function isFloat( Str )
{
   return / ^ [ + - ] ? \d + \.
   {
      1
   }
   \d * $ / .test( Str );
}

//  ---------------------------------------

function isCurrency( Str )
{
   return / ^ \d + [.]
   {
      1
   }
   [0 - 9]
   {
      2,
   }
   $ / .test( Str );
}

//  ---------------------------------------

function isDomain ( Str )
{
   // The pattern for matching all special characters.
   // These characters include ( ) < > [ ] " | \ / ~ ! @ # $ % ^ & ? ` ' : ; ,
   var specialChars = "\\(\\)<>#\\$&\\*!`\\^\\?~|/@,;:\\\\\\\"\\.\\[\\]";
   // The range of characters allowed in a username or domainname.
   // It really states which chars aren't allowed.
   var validChars = "\[^\\s" + specialChars + "\]";
   // An atom ( basically a series of  non - special characters. )
   var atom = validChars + '+';
   // The structure of a normal domain
   var domainPat = new RegExp( "^" + atom + "(\\." + atom + ")*$" );

   // Check if IP
   var ipDomainPat = /^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/;
   var IPArray = Str.match( ipDomainPat );
   if ( IPArray != null )
   {
      // this is an IP address
      for ( var i = 1; i <= 4; i ++ )
      {
         if ( IPArray[i] > 255 )
         {
            return false
         }
      }
   }
   // Check Domain
   var domainArray = Str.match( domainPat )
   if ( domainArray == null )
   {
      return false;
   }

   /* domain name seems valid, but now make sure that it ends in a
   three - letter word ( like com, edu, gov ... ) or a two - letter word,
   representing country ( uk, vn ) or a four - letter word ( .info ), and that there's a hostname preceding
   the domain or country. */

   /* Now we need to break up the domain to get a count of how many atoms
   it consists of. */
   var atomPat = new RegExp( atom, "g" )
   var domArr = Str.match( atomPat )
   var len = domArr.length
   if ( domArr[domArr.length - 1].length < 2 || domArr[domArr.length - 1].length > 4 )
   {
      // the address must end in a two letter or three letter word or four - letter word.
      return false;
   }

   // Make sure there's a host name preceding the domain.
   if ( len < 2 )
   {
      return false;
   }

   return true;
}

//  ---------------------------------------

function isUser ( Str )
{
   var specialChars = "\\(\\)<>#\\$&\\*!`\\^\\?~|/@,;:\\\\\\\"\\.\\[\\]";
   var validChars = "\[^\\s" + specialChars + "\]";
   /* The pattern applies if the "user" is a quoted string ( in
   which case, there are no rules about which characters are allowed
   and which aren't; anything goes).  E.g. "le nguyen vu"@webtome.com
   is a valid ( legal ) e - mail address. */
   var quotedUser = "(\"[ ^ \"]*\" )";
   var atom = validChars + '+'
   var word = "(" + atom + "|" + quotedUser + ")";
   var userPat = new RegExp( "^" + word + "(\\." + word + ")*$" );
   // See if "user" is valid
   if ( Str.match( userPat ) == null )
   {
      return false ;
   }
   return true;
}

//  ---------------------------------------

function isEmail ( emailStr )
{
   /* The pattern for matching fits the user@domain format. */
   var emailPat = /^(.+)@(.+)$/ ;
   var matchArray = emailStr.match( emailPat );
   if ( matchArray == null )
   {
      /* Too many / few @'s or something; basically, this address doesn't
      even fit the general mould of a valid e - mail address. */
      return false;
   }
   var user = matchArray[1];
   var domain = matchArray[2];

   // See if "user" is valid
   if ( ! isUser( user ) )
   {
      // user is not valid
      return false ;
   }

   // Check Domain
   if ( ! isDomain( domain ) )
   {
      return false;
   }
   return true;
}

//  ---------------------------------------

function openNewWindow( linkurl, imgh, imgw, s )
{
   var w = screen.availWidth;
   var h = screen.availHeight;
   var leftPos = ( w - imgw ) / 2, topPos = ( h - imgh ) / 2;
   window.open( linkurl, 'popup', 'location=0,status=0,scrollbars=' + s + ',width=' + imgw + ',height=' + imgh + ',top=' + topPos + ',left=' + leftPos );
}

//  ---------------------------------------

function getExtension( fileName )
{
   return fileName.substr( fileName.lastIndexOf( "." ) + 1 );
}
