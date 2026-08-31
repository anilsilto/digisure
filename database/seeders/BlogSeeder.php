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
            'Hasar ve Süreç',
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
