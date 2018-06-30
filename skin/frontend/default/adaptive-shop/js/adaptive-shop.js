(function($){

    var productPage = {
        
        init : function(){

            this.displayInstallments();
        },
        helperCurrency : function(number){

            var number = parseFloat(number).toFixed(2).split('.');
            number[0] = "R$ " + number[0].split(/(?=(?:...)*$)/).join('.');

            return number.join(',');
        },
        priceInstallments : function(){
            
            const $currency = document.querySelector('.price-box .special-price span.price') ? document.querySelector('.price-box span.price') : document.querySelector('.price-box span.price');
            const price = $currency.innerText
                                .replace('R$','')
                                .replace(',','.');

            var installments = 1;
            var valueInstallment = "";

            if(price >= 60 && price <= 89.99){
            
                installments = 2;
                valueInstallment = price / installments;
            }
            else if(price >= 90 && price <= 119.99){
            
                installments = 3;
                valueInstallment = price / installments;
            }
            else if(price >= 120){
            
                installments = 4;
                valueInstallment = price / installments;
            } else {
            
                return false
            }
            
            return `<div>
                        <span class="txt">At&eacute; </span>
                        <span class="cor bold">${ installments }x ${ this.helperCurrency(valueInstallment) } sem juros </span>
                        <span class="txt">no cart&atilde;o de cr&eacute;dito</span>
                    </div>
                    <br/>`;
        },
        displayInstallments : function(){

            const $containerInstallments = document.querySelector('#price-installments');

            if (this.priceInstallments() !== false){

                $containerInstallments.innerHTML = this.priceInstallments();
            } else {

                $containerInstallments.innerHTML = "";
            }
        }
    };

    document.addEventListener("DOMContentLoaded", function(event) {
    
        if (document.body.classList.contains('catalog-product-view')) {
            
            productPage.init();

            document.querySelector('.price-box').addEventListener("DOMSubtreeModified", function(event){
                
                productPage.displayInstallments();
            });
        }
    });

})(jQuery)

