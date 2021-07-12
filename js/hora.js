/* http://www.forosdelweb.com/f13/js-para-campo-texto-que-valide-horas-731157/ 
 * <input type="text" name="xxx" id="fecha" maxlength="5" onkeyup="Validar(this,':',patron,true)" value="xxx" /> */

var patron = new Array(2,2)
function Validar(elem,separador,pat,numerico) {
    if(elem.valoranterior != elem.value) { 
        valor = elem.value;
        largo = valor.length;
        valor = valor.split(separador);
        valor2 = "";
 
        for(i=0; i<valor.length; i++) {
            valor2 += valor[i]; 
        }
 
        if(numerico){
            for(j=0; j<valor2.length; j++){
                if(isNaN(valor2.charAt(j))){
                    letra = new RegExp(valor2.charAt(j),"g");
                    valor2 = valor2.replace(letra,"");
                }
            }
        }
 
        valor = "";
        valor3 = new Array();
        for(n=0; n<pat.length; n++) {
            valor3[n] = valor2.substring(0,pat[n]);
            valor2 = valor2.substr(pat[n]);
        }
 
        for(q=0; q<valor3.length; q++) {
            if(q == 0) {
                if (valor3[0] > 24) {
                    valor = "";
                }else{
                    valor = valor3[q];
                }
 
            }else{
                if(valor3[q] != "") {
                    if ((valor3[0] == 24) && (valor3[1] > 0)) {
                        valor = "00";
                    } else if (valor3[1] > 59) {
                        valor = valor3[0];
                    }else{
                        valor += separador + valor3[q];
                    }
                }
            }
        }
 
        elem.value = valor;
        elem.valoranterior = valor;
    }
}