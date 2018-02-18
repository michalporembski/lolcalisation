#Dziwne Tłumaczenia i Lokalizacje w Symfony 3 i 4

##Słowem wstępu

Tłumaczenie strony to temat bardzo błahy i wielokrotnie przerabiany przez większość osób czytających ten artykuł. Jest też tematem bardzo lekkim, doskonale pasującym na wprowadzenie w Symfony. W niniejszym artykule postaram się odrobinę usystematyzować całe zagadnienie. Możliwe, że przy odrobinie mojego szczęścia i Twojej niewiedzy, drogi czytelniku, uda Ci się tu wyczytać coś ciekawego.

W tym artykule skupimy się na Symfony w wersji 3.4 i 4.0. Zamieszczone kawałki kody były przetestowane na obu wersjach Symfony, ale aplikacja która powstałą w trakcie pisania artykułu bazuje na Symfony 4.0.

##Klasyczne podejście do problemu

Bardzo często tworząc projekt całkowicie, lub częściowo nie zwracamy uwagi na jego przyszłą internacjonalizacje, aż do pewnego dnia, gdy okazuje się, że druga wersja językowa jest już potrzeba. W przypadku projektów stworzonych zgodnie ze sztuką, niezależnie od wybranych technologii, nie powinno być to dużym problemem. Jednak czy na pewno?

Wszelkie wiadomości, etykiety, tytuły i komentarze występujące w naszych szablonach powinny być łatwo przetłumaczalne, niezależnie od tego czy tłumaczenia były planowane, czy nie. Takie dane, będące tylko i wyłącznie stałymi powinny być zgrupowane razem, w jednym bądź kilku plikach, na przykład pliku tłumaczeń. 

Co jednak z naszą bazą danych? Wprowadzone do niej produkty, kategorie czy artykuły też powinny być jakoś uwzględnione w naszych tłumaczeniach. Jaką drogę tutaj należy obrać? Na każdą tabelę z treścią która ma być tłumaczona stworzyć drugą z samymi tłumaczeniami? Może użyć jednej tabeli która będzie tłumaczyć wszystko? Może rozwiążemy ten problem za pomocą jakiegoś duplikowania rekordów w naszych tabelach? Może jednak po kolei.

##Tłumaczenie a lokalizacja

Zastanówmy się też przez chwilkę czego oczekujemy. Tłumaczenia? Lokalizacje? Przecież to nie to samo. Lokalizacja stanowi szersze zagadnienie, w zależności od wersji strony musimy nie tylko przetłumaczyć teksty, ale też często zmienić format wyświetlanych dat, w przypadku sklepu zmienić walutę, lub dokonać jakiejś innej zmiany w naszym projekcie.

##Przygotowanie

Nim zaczniemy wdrażać różne wersje językowe naszego projektu, musimy upewnić się czy rozpoczynając nad nimi pracę zachowywaliśmy rozsądny porządek. Wszelkie nasze stałe; zarówno te numeryczne, te które niedługo mają stać się ciągami tłumaczonymi na różnorakie języki, jak i wszystko inne co ma ustaloną wartość powinno być w jakiś sposób pogrupowane i co najważniejsze odseparowane od reszty kodu.

Tak jak oczywiste powinno być dla nas trzymanie dyrektyw CSS w szablonach stylów, nie zaś osadzonych w treści HTML, podobnie powinno być z tymi wszystkimi etykietami wplecionymi w nasze szablony, nie powinno być dla nas ani trochę dziwne grupowanie tego razem, choćby w plikach tłumaczeń. Co jednak z naszymi komunikatami wyjątków? Czy o nich pamiętaliśmy, czy zgrupowaliśmy je razem? To też są stałe.

Tłumaczenia tłumaczeniami, ale istnieje też problem lokalizacji. Musimy pamiętać o wyświetlaniu dat, czasem uwzględnić jakąś walutę lub jednostki miary. Tutaj też będzie nam potrzebny porządek. Upewnijmy się, że posiadamy tylko jedną usługę która przetwarza obiekty DateTime i odpowiada za ich formatowanie. Jeżeli nasz projekt jest pokryty sensownie napisanymi testami, to jest duża szansa, że obiekt ten nie jest zmienną globalną, singletonem czy inną statyczną-chimerą, lecz poprawnie wstrzykiwanym serwisem.

##Zaczynamy

Zakładając, że już posiadamy projekt w Symfony 3 i już uporaliśmy się z naszym bałaganem w stałych i w duplikowaniu różnych odpowiedzialności, to sprawa tłumaczeń i lokalizacji powinna być dość łatwa. Zacznijmy od ustalenia jakie wersje językowe są dostępne na naszej stronie. Aby było ciekawiej proponuję 2 wersje językowe: Polską i Angielską oraz 3 lokalizacje Polska, Wielką Brytanię i Stany Zjednoczone. 

Daje nam to 3 dostępne `locale`: `en_US`, `en_GB` i `pl_PL`. Warto tutaj zwrócić uwagę na format `locale`. Zastosowano format zgodny ze standardami `ISO 639-1` i `ISO 3166-1 alpha-2`. Pierwszy człon odpowiada za język, drugi za kraj. Czasem jednak nasze `locale` zawierać będzie tylko język. Korzystając z zewnętrznych bibliotek i bundli, musimy przygotować się na to, że twórcy wykorzystywanego przez nas kodu nie zawsze przestrzegają normy i standardy. Wtedy nasze locale może nie być zgodne z tymi standardami. Pokusa prostego rozwiązania, może nas skusić do dostosowania naszego projektu do błędów innych. Nie powinniśmy tego robić, czasem jednak pewnie to zrobimy.

Czas wybrać domyślne `locale`. W pliku `app/config/config.yml` lub `parameters.yml` (lub pliku `config/services.yaml` w przypadku Symfony 4) dodajmy nowy parametr:

```
parameters:
  locale: en_US
```

Będzie on odpowiadało za domyślną lokalizacje strony. 

Kolejnym krokiem jest dodanie wykorzystywania naszego nowo dodanego parametru. Do pliku `app/config/config.yml` (w przypadku Symfony 4 będzie to plik: `config/packages/translation.yaml`)

```
framework:
    translator:      { fallbacks: ["%locale%"] }
    default_locale:  "%locale%"
```
Te dwa ustawienia odpowiadają za domyślną lokalizację.

Pamiętajcie proszę, że w przypadku Symfony 4, translator nie będzie domyślnie dostępny, potrzebne będzie wywołanie: `composer require translator`.

##Organizacja podstawowych tłumaczeń

Ustawiliśmy domyślny translator, czas wybrać format tłumaczeń i zapełniać nimi odpowiednie pliki. W kwestii formatu wybieram dla nas pliki `yaml`. Teraz przygotujemy jakąś spójną koncepcje ich wypełniania. Jakakolwiek by ona nie była, będzie dobra, jeżeli będzie spójna i wszyscy członkowie projektu będą wiedzieć gdzie szukać i gdzie dodawać nowe tłumaczenia.

Jako mój przykład takiej organizacji mogę zaproponować:

```
localisation:
  buttons:
    article-add: 'Add Article'
  errors:
    incorrect-locale: 'Incorrect Locale: %locale%'
  entity:
    fields:
      content: 'Content'
  form:
    labels:
      content: 'Content'
    tooltip:
      content: 'Type article content'
  locale:
    english-gb: 'English (UK)'
    english-us: 'English (US)'
    polish-pl: 'Polish'
  messages:
    success:
      article-added: 'New article was added'
  title:
    article-list: 'Article List'
```

Koncepcja ta zakłada podzielenie tłumaczeń ze względu na funkcjonalności naszego projektu. Zakładam tutaj sortowanie tłumaczeń alfabetycznie po pełnym kluczu tłumaczenia, może wam to pomóc uniknąć przypadkowego dodanie podobnych tłumaczeń. Sugeruje wam stworzenie zbioru jakiś generycznych tłumaczeń, wspólnych dla różnych funkcjonalności projektu. Nie zamierzam też w żaden sposób sugerować wam co z punktu widzenia tłumaczeń należy uznać za odrębną funkcjonalność, zaś co jedynie za część innej. Nie znam waszych systemów.

Bardzo jednak was uczulam na to, aby nazwy tłumaczeń i ich wartości były ze sobą spójne w sensie domenowym. Starajcie się aby w tłumaczeniach i kodzie pojawiały się takie same nazwy jak w specyfikacji dostarczanej przez wasz biznes. Może was to ustrzec przed pewnymi nieporozumieniami i wieloma liniami niepotrzebnego kodu.

##Wykorzystanie translatora

Ustawiliśmy translator, wypełniliśmy plik z tłumaczeniami, czas aby nasz projekt zaczął wykorzystywać te tłumaczenia. Możemy już wstrzyknąć usługę translatora do każdej naszej klasy i cieszyć się ładnie tłumaczonym tekstem. Czy jednak na pewno wszędzie? Warto wyznaczyć sobie pewne granice, gdzie translator będzie nam niezbędny, bo wszędzie indziej lepiej aby go nie było.

Osobiście postrzegam translator, jako coś bardzo mocno związanego z widokiem. Chciałbym aby mój translator był używany tylko i wyłącznie w szablonach twig. Nie zawsze będzie to jednak możliwe. Jeżeli nasza aplikacja wystawia jakiekolwiek api, choćby dla najprostszego ajaxa, niezbędne może się okazać czasem wykorzystać translator poza szablonem. 

Kontroler? Może jednak lepiej nie, preferowałbym używać go w jakiś handlerach i niekiedy w event listnerach, ale ze względów wydajnościowych, tam też lepiej nie.

##Zbędne tłumaczenia

Wraz z upływem czasu tłumaczeń będzie coraz więcej, czasem niektóre tłumaczenia będą wychodzić z użycia, nie zawsze jednak będą one usuwane z plików tłumaczeń. Prędzej, lub jeszcze prędzej czeka nas bałagan w tych plikach. Z pomocą jednak przychodzi Symfony. W dokumentacji Symfony znajdziecie przykład komendy:

`php bin/console debug:translation en-EN AppBundle`

lub w przypadku Symfony 4:

`php bin/console debug:translation en-EN`

W kwestii porządkowania naszych tłumaczeń, może nam to pomóc, ale nie oczekujcie zbyt wiele. Im bardziej skomplikowany jest wasz projekt, a co za tym idzie im więcej błędów się spodziewacie w waszych tłumaczeniach, tym więcej fałszywych alarmów znajdziecie na wyjściu tejże komendy.

Warto też zwrócić uwagę na to, że w naszym przypadku aplikacja zakłada 2 lokalizacje anglojęzyczne. Jeżeli nasz projekt będzie wystarczająco prosty, może się okazać, że zawartość pliku `messages.en_US.yaml` i `messages.en_GB.yaml` będzie taka sama, sugeruje wtedy zamiast tworzyć 2 pliki zastosować dowiązanie symboliczne.

##Przełącznik języka

Kolejnym zagadnieniem z którym się zmierzymy jest przełącznik języka, czy też raczej lokalizacji. Najbardziej klasyczna wersja zakłada trzymanie informacji o lokalizacji w sesji i wpięciu listenera pod wydarzenie `onKernelRequest`. Pamiętajmy jednak, że dodajemy kod który będzie wykonywany przy każdym requeście. Jeżeli zależy nam na wydajności, to może to być dla nas bardzo kosztowne. 

Przykładowy listener może może wyglądać następująco:

```
namespace App\EventSubscriber;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }
        $locale = $request->getSession()->get('_locale');
        $request->setLocale($locale);
        $request->getSession()->set('_locale', $locale);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 15],
        ];
    }
}
```

Zaś w naszym `services.yml` umieścimy:

```
    app.event_subscriber.locale_subscriber:
        class: App\EventSubscriber\LocaleSubscriber
        arguments: ['@router', '%locale%']
        tags:
            - { name: kernel.event_subscriber }
```

> Upewnijcie się, że opcje `autowire` i `autoconfigure` są ustawione poprawnie.

Musimy jeszcze utworzyć akcje kontrolera odpowiedzialną za obsługę zmiany `locale`:

```
namespace App\Controller;

use App\Services\Locale;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/language")
 */
class LanguageController extends Controller
{
    const LOCALE_EXPIRATION_TIME = 604800;

    /**
     * Action for changing locale
     *
     * @Route("/change/{locale}", name="language_change")
     * @Method({"GET"})
     */
    public function changeLanguageAction(Request $request, string $locale)
    {
        $response = $this->redirectToRoute('dashboard');
        if (in_array($locale, array_keys(Locale::AVAILABLE_LOCALES))) {
            $response->headers->setCookie($this->createCookie($locale));
        } else {
            $this->addFlash(
                'error',
                $this->get('translator')->trans('localisation.errors.incorrect-locale', ['%locale%' => $locale])
            );
        }

        $request->setLocale($locale);
        $request->getSession()->set('_locale', $locale);

        return $response;
    }

    private function createCookie($locale): Cookie
    {
        return new Cookie(
            '_locale',
            $locale,
            time() + self::LOCALE_EXPIRATION_TIME,
            '/',
            null,
            false,
            false
        );
    }
}
```

W powyższym przykładzie lokalizację zapisujemy w cookie. Najprostszy przełącznik lokalizacji wyglądałby w następujący sposób:

```
<a
	href="{{ path("language_change", {'locale':  'en_US'}) }}">
	{{ "localisation.locale.english-united-states" | trans }}
</a>
```

Moglibyśmy się pokusić o przekazanie aktualnego adresu do naszej akcji kontrolera:

```
{{ path("language_change", {'locale':  'en_US', 'url':  app.request.uri|encodeUrl }) }}
```

Wtedy nasza akcja kontrolera mogłaby nas przenosić do tego adresu, zamiast do strony. 

Nasze locale możemy przechowywać też w bazie danych, osobno dla każdego użytkownika. Stosowne przerobienie kontrolera nie powinno stanowić większego problemu.

##Liczba mnoga

Niebanalnym problemem jest poprawne dobranie formy tłumaczenia w zależności od mnogości argumentu. Dobrym przypadkiem może być dla nas język polski. Wyobraźmy sobie listę wpisów, na której może być zero, jeden, dwa lub pięć elementów. Wiadomość o liczebności w każdym przypadku będzie inna. Tak więc mamy:
* Nie znaleziono wyników
* Znaleziono 1 wynik
* Znaleziono 2 wyniki
* Znaleziono 5 wyników

W różnych językach panują różne zasady, na szczęście w Symfony jest dostępny mechanizm `transchoice`. Aby poprawnie przetłumaczyć taki komunikat wystarczy w szablonie użyć prostego:
```
{% transchoice items|length %}
    localisation.message.found-records
{% endtranschoice %} 
```

Dodatkowo musimy dodać stosowne tłumaczenia, do pliku tłumaczeń, dla języka polskiego byłoby to:
```
  message:
    found-records: '{0}Nie znaleziono wyników|{1}Znaleziono 1 wynik|]1,5[Znaleziono %count% wyniki|]4,Inf[Znaleziono %count% wyników'
```

##Formatowanie dat

W naszym projekcie przydatne może okazać się poprawne formatowanie i wyświetlanie dat. Warto zwrócić uwagę na to, że w Stanach Zjednoczonych powszechnie używa się formatu: `n/j/y`, w Wielkiej Brytanii `d M Y`, w Polsce zaś `d.m.y`. Stworzymy więc w naszym projekcie serwis odpowiedzialny za formatowanie dat.

```
namespace App\Services;

use Symfony\Component\HttpFoundation\RequestStack;

class DateService
{
    const DATE_FORMAT = [
        Locale::LOCALE_EN_US => 'n/j/y',
        Locale::LOCALE_EN_GB => 'd M Y',
        Locale::LOCALE_PL_PL => 'd.m.y',
    ];

    const DATE_TIME_FORMAT = [
        Locale::LOCALE_EN_US => 'n/j/y g:i A',
        Locale::LOCALE_EN_GB => 'd M Y H:i',
        Locale::LOCALE_PL_PL => 'd.m.y H:i',
    ];

    private $currentLocale;

    public function __construct(RequestStack $requestStack)
    {
        $this->currentLocale = $requestStack->getCurrentRequest()->getLocale();
    }

    public function getCurrentTime(): \DateTime
    {
        return new \DateTime();
    }

    public function formatDate(\DateTime $dateTime): string
    {
        return $dateTime->format($this->getDateFormat());
    }

    public function formatDateTime(\DateTime $dateTime): string
    {
        return $dateTime->format($this->getDateTimeFormat());
    }

    private function getDateFormat(): string
    {
        if (isset(self::DATE_FORMAT[$this->currentLocale])) {
            return self::DATE_FORMAT[$this->currentLocale];
        }

        return self::DATE_FORMAT[Locale::DEFAULT_LOCALE];
    }

    private function getDateTimeFormat(): string
    {
        if (isset(self::DATE_TIME_FORMAT[$this->currentLocale])) {
            return self::DATE_TIME_FORMAT[$this->currentLocale];
        }

        return self::DATE_TIME_FORMAT[Locale::DEFAULT_LOCALE];
    }
}
```

##Tłumaczenie wyjątków

Kolejnym elementem podlegającym tłumaczeniu są wyjątki. PHP, Symfony i Doctrine zapewnią nam wiele różnych wyjątków. Zazwyczaj jednak, tylko niektóre wyjątki powinny trafić do użytkownika. Tak więc treść wyjątku o problemie w połączeniu z bazą danych z nazwą bazy danych, nazwą użytkownika i jego hasłem nie powinny w żadnym wypadku zostać bezmyślnie wyświetlane użytkownikowi.

Przy filtrowaniu które wyjątki będziemy wyświetlać, a które nie, proponuję zastosować zasadę białej listy. Utwórzmy własny rodzaj wyjątku i wyświetlajmy użytkownikowi tylko te wyjątki.

```
namespace App\Exception;

class ParametrizedException extends \Exception
{
    private $params;

    public function __construct($message, $params = [], $code = 0, \Exception $previous = null)
    {
        $this->params = $params;
        parent::__construct($message, $code, $previous);
    }

    final public function getParams(): array
    {
        return $this->params;
    }
}
```

Wszędzie w naszym kodzie gdzie wyrzucamy jakiś wyjątek którego treść w przypadku błędu miałaby zostać wyświetlona użytkownikowi, będziemy stosować nasz `ParametrizedException`. 

Konstruktor naszego wyjątku został zmodyfikowany, przyjmuje on tablicę parametrów, które zostaną przekazane do tłumaczenia. Przykładowy kod kontrolera który obsługiwałby taki wyjątek mógłby wyglądać następująco:

```
try{
    $this->doSomething();
} catch (ParametrizedException $e) {
        $message = $this->get('translator')
            ->trans(
                $e->getMessage(),
                $e->getParams()
            );
        $this->addFlash(
            'error',
            $message
        );
 }
```

Niektóre wyjątki chcielibyśmy zalogować, warto wtedy aby treści logowały się w domyślnym języku. Nasz kod obsługi wyjątku wyglądałby wtedy następująco:

```
try{
    $this->doSomething();
} catch (ParametrizedException $e) {
        $message = $this->get('translator')
            ->trans(
                $e->getMessage(),
                $e->getParams()
            );
        $this->addFlash(
            'error',
            $message
        );
    $this->logError($this->translateToDefaultLocale($e->getMessage(), $e->getParams());
 }

```

Zaś metoda odpowiedzialna za tłumaczenie na domyślny język:

```
protected function translateToDefaultLocale($message, array $parameters = [], $domain = null)
{
    return $this->get('translator')->trans($message, $parameters, $domain, $this->getParameter('kernel.default_locale'));
}
```

##Tłumaczenia bazy danych

Ostatnim zagadnieniem które chciałbym poruszyć są tłumaczenia treści z naszej bazy danych. To jaką drogę powinniśmy obrać zależy od specyfiki naszego projektu. 

Jeżeli nasz projekt przypomina bardziej bloga, w którym istnieją artykuły w różnych językach, gdzie nie każdy artykuł jest tłumaczony na inne języki sugerowałbym dodać do naszej tabeli z treściami kolumnę odpowiedzialną za język. 

Jeżeli trzymamy w bazie danych jakieś proste słowniki, na przykład kategorie lub tagi, można zastosować rozszerzenie do Doctrine: `Doctrine2 behavioral extensions`.

`https://github.com/Atlantic18/DoctrineExtensions/tree/master`

Daje ono nam możliwość tłumaczenia kolumn encji w locie. Nie polecam tego rozwiązania, nie będzie ono najszybsze w działaniu, może jednak okazać się dla nas wystarczające.

W Przypadku niedużych tabeli przechowujących tagi lub statusy, które nie są edytowane przez użytkownika, może warto zastosować się czy nie warto byłoby przenieść przechowywanie tłumaczonych nazw do projektu, czy to przez przechowywanie w bazie danych tylko wpisów tłumaczeń, czy też przez dodanie wszystkich wartości tych obiektów do kodu.

W przypadku danych które może edytować użytkownik, może być potrzebne stworzenie dodatkowej pośredniej tabeli na tłumaczenia, lub jeżeli z góry znamy ilość wszystkich języków dla których ma działać portal i jest ona niewielka, może wystarczy nam dodać kilka kolumn do naszej tabeli na odpowiednie tłumaczenia.

Niekiedy treści podstron różnych lokalizacji jednego serwisu nie posiadają wzajemnie swoich odpowiedników. Niekiedy nakład obliczeniowy wynikający z różnych lokalizacji strony może negatywnie wpływać na nasz serwis. Warto się wtedy zastanowić, czy przypadkiem dla nowej lokalizacji, lub wersji językowej nie warto byłoby postawić odpowiednio skonfigurowaną kopie systemu.

##Podsumowanie

Pobieżnie przeglądnęliśmy kilka zagadnień powiązanych z lokalizacją, aby ostatecznie usłyszeć radę o uciecze od problemu przez duplikację systemu. Nasze projekty są różne i wymagają różnych rozwiązań. Możliwe, że czasem zastosujemy i takie rozwiązanie.

#Źródła

https://github.com/maxpou/docker-symfony
http://atlantic18.github.io/DoctrineExtensions/doc/translatable.html
https://stackoverflow.com/questions/32832277/is-it-possible-to-do-some-sort-of-implicit-loop-with-twigs-image-to-retu
https://symfony.fi/entry/my-symfony-translations-workflow-in-2017
https://phraseapp.com/blog/posts/translate-symfony-3-apps/
http://doc.qt.io/qt-5/i18n-plural-rules.html