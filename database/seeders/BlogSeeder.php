<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriler = collect([
            'Trafik Sigortası',
            'Kasko Sigortası',
            'Sağlık Sigortası',
            'Konut & DASK',
            'İşyeri Sigortası',
            'Seyahat Sigortası',
            'Tarım Sigortası',
            'Hasar ve Süreç',
            'Genel & Rehber',
        ])->mapWithKeys(fn ($ad) => [
            $ad => BlogCategory::updateOrCreate(['slug' => Str::slug($ad)], ['name' => $ad]),
        ]);

        $cta = "\n\n---\n\nİhtiyacınıza uygun teklifleri karşılaştırmak için [birkaç dakikada teklif talebi oluşturun](/teklif).";

        $yazilar = [
            [
                'k' => 'Kasko Sigortası',
                'baslik' => 'Camıma taş sıçradı, kasko karşılar mı?',
                'ozet' => 'Ön cama taş çarpması sonucu oluşan çatlak ve kırıklar kaskonun cam kırılması teminatı kapsamındadır. Hasarsızlık indiriminizi kaybetmeden nasıl bildireceğinizi anlatıyoruz.',
                'meta' => 'Cama taş sıçradı, kasko öder mi? Cam kırılması teminatı, hasarsızlık indirimi ve anlaşmalı cam servisi süreci.',
                'govde' => <<<'MD'
Yolda seyir hâlindeyken önünüzdeki araçtan sıçrayan bir taş, ön camınızda çatlak veya kırığa yol açabilir. Bu tür hasarlar **kasko poliçesinin "cam kırılması" teminatı** kapsamındadır.

## Hangi camlar teminata dahil?

Standart kasko poliçelerinde ön cam, arka cam ve yan camlar cam kırılması teminatına girer. Sunroof (tavan camı) ve far/ayna camları çoğu poliçede ayrı bir ek teminat gerektirir; poliçenizi yaptırırken bunu sorun.

## Hasarsızlık indirimim etkilenir mi?

İyi haber: birçok sigorta şirketi, **yılda 1–2 cam hasarını hasarsızlık basamağınızı düşürmeden** karşılar. Şirketten şirkete değiştiği için poliçe özel şartlarını kontrol edin. Muafiyet (hasarın sizde kalan kısmı) uygulanıp uygulanmadığına da bakın.

## Adım adım ne yapmalısınız?

1. Camdaki çatlağı büyütmemek için aracı fazla kullanmayın.
2. Sigorta şirketinizin hasar hattını arayın; poliçe ve plaka bilginizi verin.
3. Anlaşmalı cam servisine yönlendirilirsiniz. Anlaşmalı serviste genelde fatura doğrudan şirkete kesilir, cebinizden ödeme yapmazsınız.
4. Küçük çatlaklarda cam değişimi yerine **reçine ile onarım** yapılabilir; bu hem daha hızlıdır hem de hasarsızlığınızı korur.

## Anlaşmalı servis dışında onarım yaptırırsam?

Yaptırabilirsiniz, ancak bu durumda ödeme genellikle "eksper onaylı tutar" kadar olur ve aradaki farkı siz karşılarsınız. Mümkünse anlaşmalı servisi tercih edin.
MD,
                'gun' => 2,
            ],
            [
                'k' => 'Kasko Sigortası',
                'baslik' => 'Kasko ile zorunlu trafik sigortası arasındaki fark nedir?',
                'ozet' => 'Trafik sigortası karşı tarafın zararını, kasko ise kendi aracınızın zararını karşılar. İkisinin kapsamını, zorunluluğunu ve birlikte neden yaptırıldığını açıklıyoruz.',
                'meta' => 'Kasko mu trafik sigortası mı? İkisi arasındaki fark, kapsam, zorunluluk ve hangi durumda hangisinin devreye girdiği.',
                'govde' => <<<'MD'
Çok karıştırılan bu iki poliçe birbirinin alternatifi değil, **tamamlayıcısıdır**.

## Zorunlu trafik sigortası (KTK)

Kanunen zorunludur. Kusurlu olduğunuz bir kazada **karşı tarafın** aracına, eşyasına ve bedeni zararlarına (tedavi, sakatlık, vefat) belirli limitler dahilinde teminat sağlar. Kendi aracınızın hasarını **karşılamaz**.

## Kasko

İsteğe bağlıdır. **Kendi aracınızın** başına gelenleri karşılar:

- Çarpma, çarpışma, devrilme
- Yangın ve yıldırım
- Hırsızlık ve hırsızlığa teşebbüs
- Doğal afetler (sel, dolu, fırtına — poliçede belirtilmişse)
- Cam kırılması

## Örnekle anlatalım

Kırmızı ışıkta başka bir araca çarptınız ve kusurlusunuz:

- Karşı aracın hasarı → **trafik sigortanız** öder.
- Sizin aracınızın hasarı → yalnızca **kaskonuz** varsa ödenir; trafik sigortası ödemez.

## Neden ikisi birlikte yaptırılır?

Trafik sigortası yasal zorunluluğu ve üçüncü kişilere karşı sorumluluğu; kasko ise aracınızın maddi değerini korur. Aracınız yeni veya değerliyse kasko güçlü bir tavsiyedir.
MD,
                'gun' => 5,
            ],
            [
                'k' => 'Trafik Sigortası',
                'baslik' => 'Zorunlu trafik sigortası fiyatımı ne belirler? Hasarsızlık basamağı nedir?',
                'ozet' => 'Trafik sigortası primini hasarsızlık basamağı, aracın özellikleri, il ve sürücü profili belirler. Basamağınızı yükselterek nasıl indirim kazanacağınızı anlatıyoruz.',
                'meta' => 'Trafik sigortası neden pahalı? Hasarsızlık basamağı, kademe indirimi, tavan fiyat ve primi etkileyen faktörler.',
                'govde' => <<<'MD'
Zorunlu trafik sigortası priminin nasıl hesaplandığını bilmek, gereksiz ödeme yapmanızı önler.

## Hasarsızlık (kademe) basamağı

Trafik sigortasında **8 basamaklı** bir sistem vardır. Kazasız her yıl bir üst basamağa çıkarsınız ve indiriminiz artar; kusurlu kaza yaptığınızda basamak düşer ve priminiz yükselir.

| Basamak | Etki (yaklaşık) |
|---|---|
| 4. basamak | Başlangıç seviyesi |
| 6–7. basamak | Belirgin indirim |
| 8. basamak | En yüksek indirim |

## Primi etkileyen diğer faktörler

- **Araç:** marka, model, motor gücü, koltuk sayısı, kullanım tarzı (hususi/ticari)
- **İl ve ilçe:** hasar sıklığının yüksek olduğu bölgelerde prim artar
- **Sürücü:** yaş, ehliyet yılı, geçmiş hasar kayıtları
- **Sigortalının hasar geçmişi:** son yıllarda yaptığınız kusurlu kazalar

## Basamağınızı korumanın yolları

- Küçük hasarlarda, tamir bedeli düşükse hasar açtırmadan kendiniz ödemeyi değerlendirin (basamak düşüşünün maliyeti yıllara yayılır).
- Poliçeyi kesintisiz yenileyin; arada boşluk kalırsa kademe hakkınız etkilenebilir.
- Aynı basamak birden fazla şirkette farklı prim üretir; bu yüzden her yenilemede karşılaştırma yapın.
MD,
                'gun' => 8,
            ],
            [
                'k' => 'Hasar ve Süreç',
                'baslik' => 'Trafik kazası sonrası ne yapmalı? Adım adım rehber',
                'ozet' => 'Maddi hasarlı kazada tutanak, fotoğraf ve bildirim sırası; yaralanma varsa yapılması gerekenler. Hak kaybı yaşamamak için kaza anında izlenecek yol.',
                'meta' => 'Kaza yaptım ne yapmalıyım? Kaza tespit tutanağı, fotoğraf, hasar bildirimi ve yaralı varsa izlenecek adımlar.',
                'govde' => <<<'MD'
Kaza anında panik yerine sıralı hareket etmek, hem güvenliğiniz hem de tazminat hakkınız için kritiktir.

## 1. Güvenliği sağlayın

Aracınızı mümkünse kenara çekin, dörtlüleri yakın, reflektör koyun. Yaralı varsa **112**'yi arayın; yaralıyı zorunluluk olmadıkça hareket ettirmeyin.

## 2. Yaralanma veya anlaşmazlık varsa polis/jandarma çağırın

Ölümlü/yaralanmalı kazalarda veya taraflar anlaşamıyorsa kolluk kuvveti tutanak tutar. Yalnızca maddi hasar varsa ve taraflar anlaşıyorsa **kaza tespit tutanağını kendiniz** doldurabilirsiniz.

## 3. Kanıt toplayın

- Araçların çarpışma anındaki konumunu net gösteren fotoğraflar
- Plakalar, hasarlı bölgeler, yol ve trafik işaretleri
- Karşı tarafın adı, telefonu, poliçe ve ruhsat bilgileri
- Varsa görgü tanığı iletişim bilgisi

## 4. Tutanağı eksiksiz doldurun

Kusur oranı, tarih-saat, konum ve taraf bilgileri açık olsun. İki taraf da imzalasın; birer nüsha alın.

## 5. Bildirim yapın

Kazayı **5 iş günü** içinde sigorta şirketinize bildirin. Kusurluysanız karşı taraf sizin trafik sigortanıza, kusursuzsanız siz karşı tarafın trafik sigortasına başvurursunuz. Kaskonuz varsa kendi aracınızın onarımı için kasko şirketinize de bildirim yapın.

## 6. Eksper ve onarım

Eksper hasarı inceler, onarım anlaşmalı serviste yapılır. Onardıktan sonra "hasarsız" belgesi ve fatura nüshalarını saklayın.
MD,
                'gun' => 11,
            ],
            [
                'k' => 'Kasko Sigortası',
                'baslik' => 'Aracım çalındı, kasko ne kadar öder ve ne zaman?',
                'ozet' => 'Hırsızlık teminatında ödeme, aracın çalındığı gündeki kasko değeri üzerinden yapılır. 30 günlük bekleme süresi, gerekli belgeler ve bulunma ihtimali dahil tüm süreç.',
                'meta' => 'Aracı çalınan kişi kaskodan ne alır? Hırsızlık teminatı ödeme tutarı, 30 gün bekleme süresi ve gerekli belgeler.',
                'govde' => <<<'MD'
Araç hırsızlığı, kasko poliçesinin **hırsızlık teminatı** kapsamındadır. Süreç şöyle işler:

## 1. Hemen bildirim

Aracınızın çalındığını fark ettiğiniz anda **karakola** giderek çalıntı kaydı açtırın. Ardından sigorta şirketinize bildirin.

## 2. 30 günlük bekleme süresi

Sigorta şirketi, aracın bulunma ihtimaline karşı **30 gün** bekler. Bu süre içinde araç bulunursa hasar (varsa) onarılır; bulunmazsa ödeme süreci başlar.

## Ne kadar ödenir?

Ödeme, **aracın çalındığı tarihteki kasko (rayiç) değeri** üzerinden yapılır. Poliçede yazan bedel değil, güncel piyasa değeri esas alınır. Muafiyet ve varsa eksik sigorta oranı düşülür.

## Gerekli belgeler

- Aracın tüm anahtarları (genellikle 2 adet) ve varsa yedek anahtar
- Ruhsat aslı
- Karakol çalıntı tutanağı ve "bulunamadı" yazısı
- Noterden verilen "araç satış ve devir" vekâleti (şirkete devir için)

## Araç sonradan bulunursa?

Ödeme yapıldıktan sonra araç bulunursa mülkiyet sigorta şirketine geçmiştir. Bulunan aracı geri almak isterseniz aldığınız tazminatı iade etmeniz gerekir.
MD,
                'gun' => 15,
            ],
            [
                'k' => 'Kasko Sigortası',
                'baslik' => 'Kasko değeri (rayiç bedel) nasıl belirlenir?',
                'ozet' => 'Kasko değeri, TSB Kasko Değer Listesi temel alınarak aracın marka, model, yaş ve donanımına göre belirlenir. Eksik veya aşkın sigortadan kaçınmak için bilmeniz gerekenler.',
                'meta' => 'Kasko rayiç bedeli nedir, nasıl hesaplanır? TSB Kasko Değer Listesi, eksik sigorta ve pert (hasar) eşiği.',
                'govde' => <<<'MD'
Kasko priminiz ve hasar ödemeniz, aracınızın **rayiç (kasko) değerine** dayanır.

## Değer nasıl belirlenir?

Türkiye Sigorta Birliği (TSB) her ay bir **Kasko Değer Listesi** yayımlar. Sigorta şirketleri poliçe ve hasar hesaplarında bu listeyi esas alır. Değer; marka, model, model yılı, yakıt/vites tipi ve fabrika donanımına göre değişir.

## Eksik sigorta ve aşkın sigorta

- **Eksik sigorta:** Poliçedeki bedel, gerçek rayiç değerin altındaysa hasarınız oranlı ödenir. Örneğin araç 1.000.000 TL, poliçe 800.000 TL ise hasarın yalnızca %80'i ödenebilir.
- **Aşkın sigorta:** Gerçek değerin üzerinde bedel yazdırmak fazladan prim ödetir; hasarda yine rayiç değer esas alınır.

En doğrusu, poliçe bedelinin güncel rayiç değere **eşit** olmasıdır.

## Pert (ağır hasar) eşiği

Onarım masrafı, aracın rayiç değerinin belirli bir oranını (genelde %70) aşarsa araç **pert** sayılır ve şirket rayiç değeri öder, sovtaj (hurda) bedelini düşer.

## Yenilemede dikkat

Araç her yıl değer kaybeder. Poliçeyi yenilerken bedeli güncel listeye göre düşürmek, gereksiz prim ödemenizi önler.
MD,
                'gun' => 19,
            ],
            [
                'k' => 'Trafik Sigortası',
                'baslik' => 'Aracımı sattım, trafik sigortasını iptal edip iade alabilir miyim?',
                'ozet' => 'Araç satışında noter devriyle birlikte trafik sigortası iptal edilebilir ve kalan güne ait prim iade edilir. İptal için gereken belgeler ve kasko iadesi farkları.',
                'meta' => 'Araç sattım trafik sigortası ne olur? Poliçe iptali, gün esaslı prim iadesi ve gerekli belgeler.',
                'govde' => <<<'MD'
Aracınızı sattığınızda mevcut trafik sigortası **yeni sahibe otomatik geçmez**; iptal edip kalan primi geri alabilirsiniz.

## İptal ve iade nasıl işler?

Noter satış sözleşmesi tarihinden itibaren poliçenizi iptal ettirdiğinizde, **kullanılmayan güne denk gelen prim** gün esaslı olarak iade edilir. Poliçenin başından bu yana geçen süre ve varsa yapılan hasarlar düşülür.

## Gerekli belgeler

- Noter satış/devir sözleşmesi
- Poliçe örneği
- İade için IBAN

## Kasko iadesinde fark var mı?

Kaskoda da benzer şekilde gün esaslı iade yapılır; ancak poliçe döneminde **hasar ödemesi** yapıldıysa iade tutarı önemli ölçüde azalabilir veya sıfırlanabilir.

## Yeni araç aldıysanız

Kademe (hasarsızlık) hakkınız size aittir, araca değil. Yeni aracınıza yeni bir poliçe yaptırırken mevcut basamağınızdan yararlanabilirsiniz. Bu yüzden eski poliçenizi iptal ederken kademe bilginizi not alın.
MD,
                'gun' => 23,
            ],
            [
                'k' => 'Sağlık Sigortası',
                'baslik' => 'Tamamlayıcı sağlık sigortası nedir, kimler için mantıklı?',
                'ozet' => 'Tamamlayıcı sağlık sigortası (TSS), SGK anlaşmalı özel hastanelerde fark ücreti ödemeden tedavi imkânı sunar. Özel sağlık sigortasından farkı ve kimlere uygun olduğu.',
                'meta' => 'Tamamlayıcı sağlık sigortası nedir? SGK anlaşmalı özel hastanede farksız tedavi, kapsam ve özel sağlıktan farkı.',
                'govde' => <<<'MD'
Tamamlayıcı Sağlık Sigortası (TSS), **SGK'nız devam ederken** özel hastane masraflarınızın SGK dışında kalan kısmını karşılayan bir üründür.

## Nasıl çalışır?

SGK ile anlaşmalı özel bir hastaneye gittiğinizde hastane, giderin bir kısmını SGK'dan tahsil eder; kalan **fark ücretini** normalde siz ödersiniz. TSS bu farkı üstlenir, böylece cebinizden ödeme yapmadan (veya çok az ödeyerek) tedavi olursunuz.

## Neyi kapsar?

- Yatarak tedavi (ameliyat, tetkik, yoğun bakım)
- Çoğu poliçede ayakta tedavi (muayene, tahlil, görüntüleme, ilaç) — limit/katılım payıyla
- Doğum (bekleme süresi sonrası)

## Özel sağlık sigortasından farkı

| | Tamamlayıcı (TSS) | Özel Sağlık |
|---|---|---|
| SGK şartı | Zorunlu | Gerekmez |
| Hastane ağı | SGK anlaşmalı özel hastaneler | Daha geniş, anlaşmalı kurum listesi |
| Prim | Daha uygun | Daha yüksek |

## Kimler için mantıklı?

- Özel hastane hizmeti isteyen, SGK'lı çalışanlar ve aileleri
- Bütçesini zorlamadan fark ücretlerinden kurtulmak isteyenler
- Kronik takip veya planlı ameliyatı olanlar (bekleme sürelerine dikkat)
MD,
                'gun' => 27,
            ],
            [
                'k' => 'Sağlık Sigortası',
                'baslik' => 'Özel sağlık sigortasında bekleme süresi ve "mevcut hastalık" ne demek?',
                'ozet' => 'Özel ve tamamlayıcı sağlık poliçelerinde doğum, ameliyat gibi durumlar için bekleme süreleri uygulanır; poliçe öncesi var olan rahatsızlıklar kapsam dışı bırakılabilir.',
                'meta' => 'Sağlık sigortasında bekleme süresi nedir? Mevcut hastalık istisnası, doğum bekleme süresi ve poliçe öncesi rahatsızlıklar.',
                'govde' => <<<'MD'
Sağlık sigortası teklifini değerlendirirken iki kavramı mutlaka anlamalısınız: **bekleme süresi** ve **mevcut hastalık**.

## Bekleme süresi

Poliçe başladıktan sonra belirli teminatların devreye girmesi için geçmesi gereken süredir. Tipik örnekler:

- **Doğum:** genellikle 12 ay
- Bazı planlı ameliyatlar (bel fıtığı, katarakt, tonsillektomi vb.): 3–12 ay
- Acil durumlar ve kazalar: bekleme süresi **yok**, ilk günden geçerli

## Mevcut hastalık (poliçe öncesi rahatsızlık)

Poliçe başlangıcından **önce** var olan, tanısı konmuş veya belirtileri bilinen rahatsızlıklardır. Sigorta şirketi bunları:

- Tamamen kapsam dışı bırakabilir,
- Ek prim (sürprim) ile kapsama alabilir,
- Belirli bir süre sonra kapsama dahil edebilir.

Başvuru formunda sağlık beyanınızı **eksiksiz ve doğru** doldurmak çok önemlidir; eksik beyan, hasar anında ödemenin reddine yol açabilir.

## Ömür boyu yenileme garantisi

Uzun vadede en değerli maddelerden biridir: poliçenizi kesintisiz yenilediğiniz sürece, sonradan çıkan hastalıklar nedeniyle şirket sizi poliçe dışı bırakamaz veya keyfi sürprim uygulayamaz. Teklifleri karşılaştırırken bu şartın olup olmadığına bakın.
MD,
                'gun' => 31,
            ],
            [
                'k' => 'Trafik Sigortası',
                'baslik' => 'İMM (İhtiyari Mali Mesuliyet) sigortası nedir, kimler yaptırmalı?',
                'ozet' => 'İMM, zorunlu trafik sigortasının limitleri yetmediğinde devreye giren ek sorumluluk teminatıdır. Ağır kazalarda malvarlığınızı korur; kasko ile birlikte önerilir.',
                'meta' => 'İMM sigortası nedir, ne işe yarar? Trafik sigortası limiti aşıldığında devreye giren ihtiyari mali mesuliyet teminatı.',
                'govde' => <<<'MD'
İhtiyari Mali Mesuliyet (İMM) sigortası, kusurlu olduğunuz bir kazada **zorunlu trafik sigortasının limitleri yetmediğinde** karşı tarafın zararını karşılamaya devam eden ek bir teminattır.

## Neden gerekli?

Zorunlu trafik sigortasının maddi ve bedeni zararlar için yıllık limitleri vardır. Lüks bir araca çarpmak, birden fazla kişinin yaralanması veya vefat gibi durumlarda bu limit **hızla aşılabilir**. Limit aşıldığında aradaki farkı normalde **kendi malvarlığınızdan** ödersiniz — ev, araç, maaş haczi gündeme gelebilir.

İMM tam bu noktada devreye girer ve poliçede seçtiğiniz limite kadar (örneğin 500.000 TL, 1.000.000 TL, 5.000.000 TL) ödemeyi sürdürür.

## Neyi kapsar?

- Karşı araç ve üçüncü şahısların **maddi** zararlarının trafik limiti üstü kısmı
- Genişletilmiş İMM ile: **bedeni** zararlar (tedavi, sakatlık, destekten yoksun kalma), manevi tazminat, kısmi olarak alkollü/ehliyetsiz sürüş

## Kimler yaptırmalı?

- Şehirlerarası çok yol yapanlar
- Değerli araçların yoğun olduğu bölgelerde araç kullananlar
- Kısaca: bir kaza sonrası birikimini riske atmak istemeyen herkes

İMM genellikle kasko poliçesine düşük ek primle eklenir. Teklif alırken İMM limitini de karşılaştırın.
MD,
                'gun' => 35,
            ],

            [
                'k' => 'Trafik Sigortası',
                'baslik' => 'Araç değer kaybı nedir, kusursuz taraf nasıl alır?',
                'ozet' => 'Kazada kusursuzsanız, onarım sonrası aracınızın ikinci el değerindeki düşüş için karşı tarafın trafik sigortasından tazminat isteyebilirsiniz.',
                'meta' => 'Araç değer kaybı tazminatı nedir, nasıl alınır? Kusursuz tarafın karşı sigortadan değer kaybı talebi ve zamanaşımı.',
                'govde' => <<<'MD'
Bir kazada onarılan araç, kayıt geçmişi nedeniyle ikinci el piyasada daha düşük fiyata satılır. Bu farka **değer kaybı** denir ve kusuru olmayan taraf, karşı tarafın **zorunlu trafik sigortasından** talep edebilir.

## Kimler talep edebilir?

- Kazada **kusuru olmayan** veya kusuru düşük olan taraf.
- Aracın **kayıtlı ağır hasarı** yoksa ve onarım gören parça **plastik değilse** (tampon, ayna gibi plastik parça değişimleri genelde değer kaybına konu olmaz).
- Araç belirli bir yaş ve kilometrenin altındaysa; çok eski/yüksek kilometreli araçlarda tutar düşer.

## Nasıl başvurulur?

1. Kazadan sonra karşı tarafın sigorta şirketine yazılı başvuru yapılır.
2. Şirket 15 iş günü içinde ödeme yapmalı veya gerekçeli ret vermelidir.
3. Sonuç olumsuzsa **Sigorta Tahkim Komisyonu**’na başvurulur; genellikle bilirkişi bir tutar belirler.

## Zamanaşımı

Talep hakkı kaza tarihinden itibaren **2 yıldır** (bazı durumlarda olayı ve faili öğrenmeden itibaren). Süreyi kaçırmamak için kaza sonrası erken hareket edin.
MD,
                'gun' => 4,
            ],
            [
                'k' => 'Trafik Sigortası',
                'baslik' => 'Alkollü veya ehliyetsiz kazada sigorta öder mi?',
                'ozet' => 'Trafik sigortası karşı tarafın zararını öder ama alkollü/ehliyetsiz sürücüye rücu eder; kasko ise bu durumlarda ödeme yapmayabilir.',
                'meta' => 'Alkollü kaza kasko öder mi? Ehliyetsiz sürüşte trafik sigortası ve kasko, rücu hakkı ve istisnalar.',
                'govde' => <<<'MD'
İnternette dolaşan “alkollüyken de öder” bilgisi yarı doğru, yarı yanlıştır. İki poliçeyi ayrı ayrı ele almak gerekir.

## Zorunlu trafik sigortası

Mağdur üçüncü şahsı korumak için, alkollü veya ehliyetsiz sürüşte bile **karşı tarafın zararını öder**. Ancak sigorta şirketi ödediği tutarı, kusurlu sürücüye **rücu eder** (geri ister). Yani zarar cebinizden çıkar.

## Kasko

Kasko poliçelerinde alkol ve ehliyetsizlik çoğunlukla **istisnadır**: kendi aracınızın hasarı ödenmeyebilir. Genişletilmiş bazı paketlerde belirli promil sınırına kadar teminat verilir; poliçe özel şartlarını kontrol edin.

## Yetersiz ehliyet

Aracın sınıfına uygun olmayan ehliyetle (örneğin B sınıfıyla kamyon) kullanım da benzer sonuç doğurur: trafik sigortası öder ve rücu eder, kasko genellikle ödemez.

**Özet:** Alkollü/ehliyetsiz sürüş, sigorta güvencenizi fiilen ortadan kaldırır.
MD,
                'gun' => 9,
            ],
            [
                'k' => 'Hasar ve Süreç',
                'baslik' => 'Kaza tutanağında sık yapılan hatalar',
                'ozet' => 'Yanlış doldurulan bir kaza tespit tutanağı, kusursuz sürücüyü kusurlu duruma düşürebilir. Krokiden imzaya dikkat edilecek noktalar.',
                'meta' => 'Kaza tespit tutanağı nasıl doldurulur? Sık yapılan hatalar, kroki, kusur oranı ve TRAMER değerlendirmesi.',
                'govde' => <<<'MD'
Maddi hasarlı ve tarafların anlaştığı kazalarda tutanağı sürücüler doldurur. Küçük bir eksik, TRAMER (SBM) değerlendirmesinde kusur oranınızı olumsuz etkiler.

## En sık hatalar

- **Krokiyi çizmemek veya yanlış çizmek.** Araçların çarpışma anındaki konumu, şeritler ve yön okları net olmalı.
- **Kusur oranını boş bırakmak** ya da baskıyla “yarı yarıya” yazmak.
- Karşı tarafın **plaka, poliçe ve ruhsat** bilgilerini eksik almak.
- **Tarih, saat ve konumu** yazmamak.
- Tek nüsha doldurup imzalatmadan ayrılmak. İki taraf da imzalamalı, herkes bir nüsha almalı.

## Ne zaman polis çağırılmalı?

Yaralanma varsa, taraflar anlaşamıyorsa, sürücülerden biri alkollüyse veya kamu malına zarar geldiyse tutanağı **kolluk kuvveti** tutmalıdır.

## Fotoğraf

Tutanak kadar önemli: çarpışma açısını gösteren geniş kareler, hasarlı bölgeler, fren izleri ve trafik işaretleri. Bunlar itiraz aşamasında lehinize kanıt olur.
MD,
                'gun' => 12,
            ],
            [
                'k' => 'Kasko Sigortası',
                'baslik' => 'Küçük kazada kasko mu, cebden mi ödemek mantıklı?',
                'ozet' => 'Ufak bir çizik veya göçükte kasko açtırmak hasarsızlık kademenizi düşürür. Basit bir hesapla hangisinin ucuz olduğuna karar verin.',
                'meta' => 'Küçük hasarda kasko açtırmak mantıklı mı? Hasarsızlık indirimi kaybı, kademe düşüşü ve örnek hesap.',
                'govde' => <<<'MD'
Tamponunuz sürttü, aynanız çizildi. “Kaskodan mı yaptırayım, kendim mi öderim?” sorusunun cevabı basit bir karşılaştırmada.

## Kasko açtırırsanız ne kaybedersiniz?

- **Hasarsızlık kademeniz düşer.** Bir sonraki yıl priminiz belirgin şekilde artar ve bu artış birkaç yıl sürebilir.
- Poliçenizde **muafiyet** varsa, hasarın bir kısmını zaten siz ödersiniz.

## Nasıl karar verilir?

Şöyle bir toplam çıkarın:

1. Onarımın tahmini bedeli.
2. Muafiyet tutarı (varsa).
3. Önümüzdeki 2–3 yılda kademe düşüşünden doğacak **ek prim farkı**.

Onarım bedeli, (2) + (3) toplamından düşükse **cebinizden ödemek** daha ekonomiktir. Yüksekse kaskoyu kullanın.

## İpucu

Cam kırılması gibi bazı teminatlar çoğu poliçede kademeyi etkilemez; bunları çekinmeden kullanabilirsiniz.
MD,
                'gun' => 14,
            ],
            [
                'k' => 'Kasko Sigortası',
                'baslik' => 'Sonradan takılan jant, multimedya, cam filmi kaskoya işler mi?',
                'ozet' => 'Araca sonradan eklenen aksesuarlar, poliçeye ayrıca bildirilip bedeli eklenmediyse hasar anında ödenmez.',
                'meta' => 'Aksesuar kasko teminatı: çelik jant, ses sistemi, cam filmi. Sonradan eklenen ekipman hasar anında ödenir mi?',
                'govde' => <<<'MD'
Fabrika çıkışı standart donanım kasko bedeline dahildir. **Sonradan** takılan ekipman için ayrı bir kural işler.

## Neden ayrıca bildirilmeli?

Kasko bedeli, aracın TSB Kasko Değer Listesi’ndeki standart hâline göre belirlenir. Çelik jant, gelişmiş multimedya, hi-fi ses sistemi, LPG, cam filmi veya kaplama gibi ilaveler bu bedele **dahil değildir**. Hasar veya hırsızlıkta bunların bedelini alabilmek için:

1. Poliçe yapılırken aksesuarları **beyan edin**.
2. Fatura veya değer tespitiyle **ek teminat** olarak poliçeye ekletin (genellikle küçük bir ek prim).

## Bildirilmezse ne olur?

Aracınız çalınır veya yanarsa, sigorta yalnızca standart araç bedelini öder; binlerce liralık aksesuar zararı sizde kalır.

## LPG özel durumu

LPG sistemi hem güvenlik hem bedel açısından mutlaka poliçede yer almalıdır; bildirilmemiş LPG bazı şirketlerde hasarın reddine gerekçe olabilir.
MD,
                'gun' => 17,
            ],
            [
                'k' => 'Kasko Sigortası',
                'baslik' => 'Dolu yağışında araç hasarını kasko karşılar mı?',
                'ozet' => 'Dolu, kasko poliçelerinde doğal afet teminatı kapsamındadır. Teminatın açık yazılı olması ve hasarın zamanında bildirilmesi gerekir.',
                'meta' => 'Dolu hasarı kasko öder mi? Doğal afet teminatı, PDR (boyasız göçük düzeltme) ve hasarsızlığa etkisi.',
                'govde' => <<<'MD'
Birkaç dakikalık şiddetli dolu, kaputta ve tavanda yüzlerce göçük bırakıp yüksek onarım maliyeti çıkarabilir.

## Teminat durumu

Dolu; sel, fırtına ve yıldırımla birlikte **doğal afet** teminatı altındadır. Tam kasko poliçelerinde genellikle standarttır; dar kasko veya sınırlı paketlerde **açıkça yazılı** olup olmadığını kontrol edin.

## Onarım yöntemi

Boya bozulmadıysa **PDR (boyasız göçük düzeltme)** uygulanır; bu hem hızlıdır hem de aracın orijinal boyasını korur, değer kaybını azaltır. Boya çatladıysa panel boyası gerekir.

## Hasarsızlığa etkisi

Doğal afet hasarları çoğu şirkette **kusur içermediği için** hasarsızlık kademesini standart bir çarpışma kadar etkilemez; yine de poliçe özel şartlarına bakın.

## Ne yapmalısınız?

Yağış diner dinmez fotoğraf çekin, aracı kapalı alana alın ve şirketinize bildirin. Toplu dolu olaylarında eksper randevuları yoğunlaşır, erken başvuru avantaj sağlar.
MD,
                'gun' => 20,
            ],
            [
                'k' => 'Sağlık Sigortası',
                'baslik' => 'TSS mi ÖSS mi? Hangisi size uygun',
                'ozet' => 'Seçim; SGK durumunuza, bütçenize ve hangi hastanelerde tedavi olmak istediğinize bağlıdır. İkisini net bir tabloyla karşılaştırıyoruz.',
                'meta' => 'TSS mi ÖSS mi? Tamamlayıcı ve özel sağlık sigortası karşılaştırması, prim, hastane ağı ve kimlere uygun olduğu.',
                'govde' => <<<'MD'
İki ürün de özel hastane masrafını hafifletir ama farklı mantıkla çalışır.

## Tamamlayıcı Sağlık Sigortası (TSS)

- **SGK’lı olmak şarttır.**
- SGK anlaşmalı özel hastanede, SGK’nın karşılamadığı **fark ücretini** öder.
- Primi **uygundur**, en yaygın tercih.
- Hastane seçimi SGK anlaşmalı özel hastanelerle sınırlıdır.

## Özel Sağlık Sigortası (ÖSS)

- **SGK şartı yoktur.**
- Şirketin anlaşmalı kurum listesindeki hastanelerde geniş teminatla tedavi sağlar.
- Primi **daha yüksek**, teminat ve hastane ağı **daha esnektir**.
- Yurt dışı tedavi, geniş ayakta tedavi limiti gibi seçenekler sunabilir.

## Kısa karar rehberi

- SGK’lı çalışan/emekli + özel hastane istiyorsanız → **TSS** genellikle yeterli ve ekonomik.
- SGK’sız, çok geniş hastane ağı ve yüksek limit istiyorsanız → **ÖSS**.
- Kronik takip veya planlı ameliyat varsa → bekleme süreleri ve mevcut hastalık şartlarına ikisinde de dikkat.
MD,
                'gun' => 6,
            ],
            [
                'k' => 'Sağlık Sigortası',
                'baslik' => 'Geçmiş hastalık ve TSS: doğru beyan neden önemli?',
                'ozet' => 'Poliçe öncesi rahatsızlıklar sağlık sigortasında istisna olabilir. E-Nabız kayıtları görünür olduğundan, başvuruda eksik beyan hasar ödemesini riske atar.',
                'meta' => 'Geçmiş hastalıkla TSS alınır mı? Mevcut hastalık istisnası, sağlık beyanı ve e-nabız kayıtlarının rolü.',
                'govde' => <<<'MD'
Sağlık sigortasında en kritik konu, poliçe **başlamadan önce** var olan rahatsızlıklardır.

## Mevcut hastalık ne demek?

Tanısı konmuş veya belirtileri bilinen, poliçe öncesi rahatsızlıklardır. Sigorta şirketi bunları:

- Tamamen **kapsam dışı** bırakabilir,
- **Ek prim** (sürprim) ile kapsayabilir,
- Belirli bir süre sonra dahil edebilir.

## Beyan neden doğru olmalı?

Başvuru formundaki sağlık beyanı yanlış veya eksikse, hasar anında şirket kaydı tespit edip **ödemeyi reddedebilir** ve poliçeyi iptal edebilir. Sağlık geçmişi sistem üzerinden görülebildiği için “yazmasam belli olmaz” yaklaşımı risklidir.

## Ne yapmalısınız?

- Geçmiş tanılarınızı, kullandığınız ilaçları ve ameliyatları eksiksiz yazın.
- Sürprimli de olsa kapsama alınan bir teminat, hiç kapsanmayandan iyidir.
- **Ömür boyu yenileme garantisi** olan poliçelerde, kesintisiz yenilediğiniz sürece sonradan çıkan hastalıklar için şirket sizi dışlayamaz.
MD,
                'gun' => 10,
            ],
            [
                'k' => 'Konut & DASK',
                'baslik' => 'DASK evin gerçek değerini öder mi? Konut sigortası neden şart',
                'ozet' => 'DASK yalnızca yapının belirli bir azami tutara kadar deprem hasarını karşılar. Eşya, hırsızlık, yangın ve komşuya verilen zarar için konut sigortası gerekir.',
                'meta' => 'DASK yeterli mi? DASK azami teminat, konut sigortası farkı, eşya ve mesuliyet teminatları.',
                'govde' => <<<'MD'
“DASK’ım var, kafam rahat” cümlesi büyük bir yanılgı olabilir.

## DASK ne yapar, ne yapmaz?

- **Yapar:** Sadece **deprem ve deprem kaynaklı** (yangın, infilak, tsunami, yer kayması) hasarları, **binanın** kendisi için, her yıl güncellenen bir **azami teminat tutarına** kadar karşılar.
- **Yapmaz:** Eşyalarınızı, hırsızlığı, su baskınını, deprem dışı yangını, cam kırılmasını, komşuya verdiğiniz zararı ve azami tutarın üzerindeki bina değerini karşılamaz.

## Konut sigortası neyi ekler?

- Bina + **eşya** için gerçek değere yakın teminat.
- Yangın, dâhili su, sel, hırsızlık, cam kırılması.
- **Komşuya/üçüncü kişiye verilen zarar** (mesuliyet).
- İzolasyon eksikliğinden komşuya sızan su gibi günlük olaylar.
- İsteğe bağlı: kira kaybı, elektronik cihaz, ferdi kaza.

## Sonuç

DASK **zorunlu ve tamamlayıcıdır**; ancak tek başına evinizi ve içindekileri korumaz. Doğru koruma DASK + konut sigortası birlikteliğidir.
MD,
                'gun' => 13,
            ],
            [
                'k' => 'Konut & DASK',
                'baslik' => 'Su baskınında hasarı kim öder: kiracı mı, ev sahibi mi?',
                'ozet' => 'Sorumluluk, suyun kaynağına ve kusura göre değişir. Bina tesisatı ev sahibinin, kullanıcı kusuru kiracının, sızıntı zararı ise komşunun poliçesini ilgilendirir.',
                'meta' => 'Su baskını hasarını kim öder? Kiracı ve ev sahibi sorumluluğu, dâhili su teminatı ve komşuya sızan su.',
                'govde' => <<<'MD'
Üst kattan su indi ya da sizin banyodan alt komşuya sızdı. Masrafı kimin karşılayacağı birkaç soruya bağlı.

## 1. Su nereden geldi?

- **Bina ana tesisatı / çatı / kolon:** Genelde **ev sahibinin** ve bina yönetiminin sorumluluğu. Ev sahibinin konut sigortasındaki **dâhili su** teminatı devreye girer.
- **Daire içi kullanım kusuru** (açık kalan musluk, bakımsız hortum): Kusurlu **kullanıcının** sorumluluğu; kiracıysa kiracının.

## 2. Zarar kimde?

- **Kendi eşyanızda:** Kendi konut/eşya sigortanızın hırsızlık-dışı su teminatı.
- **Alt/yan komşunun evinde:** Sizin poliçenizdeki **mesuliyet (komşuya verilen zarar)** teminatı öder.

## 3. Hemen ne yapılmalı?

Ana vanayı kapatın, elektriği kesin, fotoğraf ve video çekin, bina yönetimine ve sigorta şirketinize bildirin. Kurutma ve onarım için eksper görüşü beklenmeli.

## Ders

Hem ev sahibi hem kiracı için **mesuliyet teminatlı** bir poliçe, komşuluk anlaşmazlıklarını sigortaya taşır.
MD,
                'gun' => 16,
            ],
            [
                'k' => 'İşyeri Sigortası',
                'baslik' => 'Yangın veya su baskını işyerini batırır mı?',
                'ozet' => 'İş yeri paket sigortası; bina ve demirbaşın yanında iş durması, ciro kaybı, cam, hırsızlık ve üçüncü şahıs mesuliyetini de kapsayabilir.',
                'meta' => 'İşyeri sigortası neleri kapsar? Yangın, su baskını, iş durması / ciro kaybı, hırsızlık ve mesuliyet teminatları.',
                'govde' => <<<'MD'
Küçük bir yangın veya su baskını yalnızca demirbaşı değil, **işin sürekliliğini** de vurur. İş yeri paket poliçesi bu riskleri tek çatı altında toplar.

## Temel teminatlar

- **Yangın, infilak, yıldırım**
- **Dâhili su, sel, fırtına, dolu**
- **Hırsızlık** (emtia + kasa muhteviyatı, limitli)
- **Cam kırılması**
- **Deprem** (ek teminat)

## Çoğu işletmenin atladığı: iş durması / ciro kaybı

Yangın sonrası dükkân 2 ay kapalı kalırsa kira, maaş ve sabit giderler devam eder, gelir durur. **Kâr kaybı / iş durması** teminatı bu dönemdeki mali kaybı karşılar. Poliçede yer alması için ayrıca talep edilmelidir.

## Mesuliyet

Müşteri iş yerinizde düşüp yaralanırsa veya komşu iş yerine zarar verirseniz, **üçüncü şahıs mali mesuliyet** teminatı devreye girer.

## Eksik sigorta tuzağı

Stok ve demirbaş bedelini düşük göstermek primi azaltır ama hasarda **oranlı ödeme** yapılır. Envanteri gerçekçi beyan edin.
MD,
                'gun' => 22,
            ],
            [
                'k' => 'Seyahat Sigortası',
                'baslik' => 'Schengen vizesi için seyahat sağlık sigortası nasıl olmalı?',
                'ozet' => 'Schengen başvurusu, tüm ülkelerde geçerli, en az 30.000 € teminatlı ve seyahat tarihlerini kapsayan bir seyahat sağlık sigortası ister. Eksik poliçe ret sebebidir.',
                'meta' => 'Schengen vizesi seyahat sağlık sigortası şartları: 30.000 euro teminat, geçerlilik alanı ve sık yapılan hatalar.',
                'govde' => <<<'MD'
Vize dosyasındaki en sık eksik, hatalı düzenlenmiş seyahat sağlık sigortasıdır.

## Konsolosluğun aradığı şartlar

- **Tüm Schengen ülkelerinde** geçerli olmalı.
- Asgari teminat **30.000 €** (tıbbi tedavi ve ülkeye geri gönderme dâhil).
- **Seyahatin tamamını** kapsamalı; giriş-çıkış tarihlerini birer gün taşırması önerilir.
- Çok girişli/uzun dönem başvurularda, ilk seyahati kapsayan poliçe + sonraki seyahatlerde yenileme taahhüdü istenebilir.

## Sık yapılan hatalar

- Poliçe tarihinin seyahat tarihinden **kısa** olması.
- Teminatın 30.000 €’nun altında olması.
- Yalnızca “kaza” teminatı; **hastalık** teminatının olmaması.
- İsim/pasaport numarasında yazım hatası.

## İpucu

Vize reddi veya seyahat iptali ihtimaline karşı poliçeyi başvurudan hemen önce yaptırın; birçok üründe vize reddinde iade seçeneği vardır.
MD,
                'gun' => 26,
            ],
            [
                'k' => 'Tarım Sigortası',
                'baslik' => 'TARSİM nedir, neleri karşılar?',
                'ozet' => 'Devlet Destekli Tarım Sigortaları (TARSİM), bitkisel ürün, sera, hayvan ve su ürünleri risklerini prim desteğiyle güvence altına alan sistemdir.',
                'meta' => 'TARSİM nedir, hangi riskleri karşılar? Devlet destekli tarım sigortası kapsamı, prim desteği ve başvuru.',
                'govde' => <<<'MD'
TARSİM, çiftçinin dolu, don, sel gibi doğa olaylarına karşı korunmasını sağlayan, primin önemli bir kısmının **devlet tarafından karşılandığı** bir sistemdir.

## Başlıca branşlar

- **Bitkisel ürün:** Dolu, fırtına, hortum, yangın; paket seçimine göre **don, sel, kuraklık** gibi ek riskler.
- **Sera:** Yapı ve içindeki ürün için dolu, fırtına, kar ağırlığı, sel.
- **Büyükbaş / küçükbaş hayvan hayat:** Hastalık, kaza, doğum riskleri ve zorunlu kesim.
- **Kümes hayvanları ve su ürünleri:** Sürü/stok bazında ölüm ve imha riskleri.

## Prim desteği

Poliçe priminin belirli bir oranı (branşa göre değişir) devlet tarafından ödenir; çiftçi yalnızca kalan kısmı öder.

## Başvuru

Kayıt için **Çiftçi Kayıt Sistemi (ÇKS)**, hayvancılıkta ilgili kayıt sistemleri güncel olmalıdır. Poliçe, riskin gerçekleşme dönemi başlamadan yaptırılmalıdır (örneğin dolu için ürün gelişme döneminden önce).
MD,
                'gun' => 29,
            ],
            [
                'k' => 'Genel & Rehber',
                'baslik' => '2026 araç satışında noter ve trafik sigortası kuralları',
                'ozet' => 'Araç devri noterde yapılır; satış sonrası trafik sigortası alıcıya otomatik geçmez. Satıcı poliçeyi iptal ettirip kalan primi iade alabilir.',
                'meta' => 'Araç satışında noter işlemi ve trafik sigortası: devir sonrası poliçe ne olur, iade nasıl alınır, dikkat edilecekler.',
                'govde' => <<<'MD'
İkinci el araç alım-satımında en çok karıştırılan konu, mevcut sigortaların devir sonrası durumudur.

## Noter devri

Araç mülkiyeti yalnızca **noterde** yapılan satış sözleşmesiyle geçer. Devir anında araç üzerinde **ödenmemiş vergi ve trafik cezası** bulunmamalıdır.

## Trafik sigortası ne olur?

- Satıcının poliçesi **alıcıya otomatik geçmez**.
- Satıcı, noter sözleşmesiyle poliçeyi **iptal ettirip** kullanılmayan güne ait primi **gün esaslı** geri alabilir.
- Alıcı, aracı devraldığı tarihten itibaren **kendi adına** yeni bir trafik sigortası yaptırmak zorundadır; sigortasız kullanım cezası araç sahibine kesilir.

## Kaskoda durum

Kasko da gün esaslı iade edilir; ancak poliçe döneminde hasar ödemesi yapıldıysa iade tutarı düşebilir veya sıfırlanabilir.

## Hasarsızlık kademesi

Kademe hakkı **araca değil, sigortalıya** aittir. Yeni aracınıza poliçe yaptırırken mevcut basamağınızdan yararlanabilirsiniz; eski poliçeyi iptal ederken kademe bilginizi not alın.
MD,
                'gun' => 32,
            ],
        ];

        // Kategoriye göre kapak görseli (public/img/ altında hazır)
        $kapaklar = [
            'Kasko Sigortası' => 'img/kasko.jpeg',
            'Sağlık Sigortası' => 'img/saglik.jpeg',
            'Hasar ve Süreç' => 'img/hasar.jpeg',
            'Trafik Sigortası' => 'img/trafik.jpeg',
        ];

        foreach ($yazilar as $y) {
            $kapak = $kapaklar[$y['k']] ?? null;

            Post::updateOrCreate(
                ['slug' => Str::slug($y['baslik'])],
                [
                    'blog_category_id' => $kategoriler[$y['k']]->id,
                    'title' => $y['baslik'],
                    'excerpt' => $y['ozet'],
                    'body' => $y['govde'].$cta,
                    'meta_description' => $y['meta'],
                    'cover_path' => ($kapak && file_exists(public_path($kapak))) ? $kapak : null,
                    'status' => 'yayinda',
                    'published_at' => now()->subDays($y['gun']),
                ],
            );
        }
    }
}
