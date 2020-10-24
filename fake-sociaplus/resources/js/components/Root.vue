<template>
    <v-app>
        <v-app-bar app dark>
            <v-app-bar-nav-icon></v-app-bar-nav-icon>
            <v-toolbar-title><router-link to="/">Football Manager</router-link></v-toolbar-title>
        </v-app-bar>
        <v-main>
            <v-container>
                <router-view></router-view>
            </v-container>
        </v-main>
        <v-spacer/>
        <v-footer app>
            <marquee-text :duration="120">
                ============= <b>Özgür Turhan Hakemlere isyan etti!!</b> -> Ya bu adam niye VAR'a gidiyor ya itiraz eden var mı doğru bir kararı gidip birde televizyondan ekstra izlemeye gerek var mı karar zaten doğru ben zaten güveniyorum federasyon varya bak boşa lig oynatıyor yani hiç gerek yok belli ki bu hakeme malup olmuş bu adam 34 hafta istiareye yatsın maçtan önce iddiaa oranların bir olması lazım herkese bir bir bir herkese eşit şekilde olması lazım çok adaletli yönetiyor aaa nasıl olur ya penaltı demek yüzde yüz doğru adamla arasında beş metre var ama demek ki niyeti kötü kırmızı kart yediğine göre bir tane yanlış karar vermez bir tane ya bir taçı da yanlış gör kardeşim yanlışım de hata yapmışım de yok bulamazsın ya bu adama sadece sarı kırmızı yetmez buna ver ara tonu ver turkuazı ver turuncuyu bak bakayım maç ne oluyor avengersta öyle bir karar verildi ki iron man şu an yanımda oturuyor beraber gs fb maçını izliyoruz suç ve cezayı birde senden dinlemek isterim bu adamın yönettiği maçı televizyonda sinemada göstermek lazım bir festivale gitse en iyi adalet ödülünü alır umarım bu penaltı uygarlığımızın gelişmesinde ülkeimizin muasır medeniyetler seviyesine ulaşmasında... sıçayım kuvvetler ayrılığı ilkesinde be yasama da sensin yürütmede sensin yargı da sensin senin kararını beğenmeyenin passo ligi gö... atıyorum mahkemem var adalet sarayına gideceğim açıyorum navigasyonu adalet sarayı yazıyorum bir gidiyorum Cüneyt Çakırın evi Allah Allah Patrona Halil isyanını avantaja bırakmış ismet inönü ikinci dünya savaşına gireyim mi girmeyeyim mi diye sormuş paşam girmeyelim demiş ismet inönü demiş ki tamam girmiyelim tamam ben paşayım ama fifa kokartı yok normal hakemler powerbankse bu hakem chernobil bak maçta dokuz tane kırmızı kart var sekiz tane kırmızı kart ver maç böyle olmaz bakın çok ince detaylar var sizin bilmediğiniz maçı öyle bir yönetiyor ki amerika ortadoğuyu böyle yönetmiyor o kadar adaletli yönetmişki topla oynama yüzde elli elli sovyet rusya da eskiden komünist rusyada maçlar 0-0 bitiyor niye adaletli her şey her şey adaletli öyle kapitalizm gibi 8-2lik 8-3lük maç yok top içeride mi dışarı da mı önemli değil çizgisiz bir dünya hayal ediyoruz biz derler ya bir elin verdiğini öbür el görmeyecek ağzını kapatıyor ki millet görmesin yapmış olduğu iyilikleri nasıl bir sevap işledim ki ben her maç bu hakemi gösteriyorsun bana adam el var diyor kardeşim el yok mu el diye bir organ yok mu var karar ofsayt tamam ama ben biliyorum ama bunu izlemeyenler nereden bilecek bu kararın birleşmiş milletler tarafından tüm insanlığa duyurulması lazım uganda bilmiyor afrikadaki insanlar bilmiyor bu kararı güney amerika kuzey amerika kutuplar dünyadaki bütün kıtalar yetti mi yetmedi güneş sistemi evren paralel evren paralel yapılanma paralel örgüt ALO! TEBEMEMEMEMEME mi ? herkes televizyonunu açsın çok önemli bir karar var bein sportsu açın evet aylık 120 lira bluetooh hoparlörle uzaya degaj dikmemiz lazım o degajı da volkan demirelin dikmesi lazım volkan demirel dikecek ki o degaj dünya dışı varlıklarla iletişime geçsin çünkü atmosferden o degajın iyi atılması lazım volkan demirel öyle atar degajları atmosfere doğru atar arkadaşlar bakın ya eğer  yaşadığımız dünya bir simüslasyon da cüneyt çakır o bilgisayarın içindeki işlemciyse ve çok ısındıysa ama işlemci soğutucusu o simülasyonu yeteri kadar karşılayamıyorsa...
            </marquee-text>
            <marquee-text :duration="60">
                --- Takım sayısı mümkünse 2'nin karesi olsun. Yoksa sapıtıyor. Şimdi ise son dakika haberleri: <b>Ekonomi</b> Dolar yine rekor kırdı. ----- <b>Ekonomi</b> Ekonomi bakanı albayrak ben dolara localhosttan bakıyorum dedi ---------- Yüro 2 lira, Dölar 0.5 KR ----
            </marquee-text>
        </v-footer>
    </v-app>
</template>

<script>
export default {
    mounted() {
        console.log('Component mounted.')
    }
}
</script>
