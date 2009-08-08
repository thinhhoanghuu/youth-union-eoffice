// Code JavaScript for Block Weblinks Module - http : // mangvn.org
d = avx.length;
if( numlg > d )
{
   numlg = d;
}

//  ---------------------------------------

var rd;
rd = ( rnd == "y" ) ? Math.floor( d * Math.random() ) : 0;

for( i = 0; i < d; i ++ )
{
   j = i ;
   if( j >= d ) j = j - d;
   document.write( '<img style="display:none; visibility:hidden" src="'+avy[j]+'"/>' );
}

//  ---------------------------------------

st = '';
str = '';
function logoref( st2 )
{
   for( i = st2; i < st2 + numlg; i ++ )
   {
      j = i;
      space2 = ( i == st2 + numlg - 1 ) ? '' : space;
      if( j >= avx.length ) j = j - avx.length;
      margin = Math.round( margin / 2 );
      str += '<a href="'+avx[j]+'" target=' + target + ' title="'+avx[j]+'"><img alt="'+avx[j]+'" width="'+width+'" border="'+border+'" height="'+height+'" src="'+avy[j]+'" style="margin:'+margin+'"/></a>' + space2;
   }
   document.getElementById( "logoexc" ).innerHTML = str ;
   st = st + numlg;
   if ( st >= avx.length )
   {
      st = st - avx.length;

   }
   str = '';
   setTimeout( "Refresh()", intervan * 600 );
}

//  ---------------------------------------

function Refresh()
{
   if( st == '' ) st = rd;
   setTimeout( "logoref(st)", intervan * 400 );
}

//  ---------------------------------------

onLoad = Refresh();
