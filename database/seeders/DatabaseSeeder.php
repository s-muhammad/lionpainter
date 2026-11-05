<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Project;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->admin();
        $this->services();
        $this->projects();
        $this->testimonials();
        $this->posts();
        $this->faqs();
        $this->settings();
        $this->enquiries();

        Setting::flush();
    }

    private function admin(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@lionpainters.com.au')],
            [
                'name' => 'مدیر لاین پینترز',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'LionPainters2026!')),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }

    private function services(): void
    {
        $services = [
            [
                'title' => 'Interior Painting',
                'slug' => 'interior-painting',
                'icon' => 'brush',
                'tagline' => 'Considered colour, flawless walls',
                'image' => 'images/project-toorak.svg',
                'excerpt' => 'Walls, ceilings and trim finished with hand-cut lines, premium low-VOC paints and the patience a beautiful room deserves.',
                'description' => <<<'HTML'
<p>Interior painting is where a home reveals its character. Our team approaches every room as a complete composition — light, architecture, furniture and colour considered together before a single drop sheet is laid.</p>
<p>We specialise in the finishes that are hardest to get right: crisp ceiling lines, smooth plasterwork, satin trim and walls that read as velvet in daylight. Low-VOC premium paints from Dulux and Haymes keep the air clean while we work.</p>
<h2>What you can expect</h2>
<p>A dedicated crew, daily clean-up and a detailed colour consultation. We protect floors, furniture and fixtures, then hand back a room that feels calmer, brighter and unmistakably finished.</p>
HTML,
                'features' => [
                    'Walls, ceilings, cornices and feature walls',
                    'Doors, skirting boards, architraves and windows',
                    'Plaster repair and crack filling before painting',
                    'Colour and sheen consultation',
                    'Low-VOC, washable premium finishes',
                    'Full furniture protection and daily clean-up',
                ],
                'sort_order' => 1,
                'is_featured' => true,
                'meta_title' => 'Interior Painting Melbourne | Lion Painters',
                'meta_description' => 'Premium interior painting across Melbourne. Flawless walls, hand-cut lines and low-VOC paints, backed by a 10-year workmanship guarantee.',
            ],
            [
                'title' => 'Exterior Painting',
                'slug' => 'exterior-painting',
                'icon' => 'home',
                'tagline' => 'Weatherproof beauty, inside out',
                'image' => 'images/project-hawthorn.svg',
                'excerpt' => 'Facades, render, weatherboards and eaves protected with coatings chosen for Melbourne’s sun, wind and rain.',
                'description' => <<<'HTML'
<p>A Melbourne facade works hard. Sun, wind, rain and temperature swings test every surface, which is why exterior painting is as much about protection as it is about presentation.</p>
<p>We begin with a thorough moisture and substrate assessment, then specify the right preparation and coating system — acrylics for weatherboards, elastomeric fills for render, and UV-stable finishes for sun-facing elevations.</p>
<h2>Built to last</h2>
<p>From heritage terraces to modern render, our exterior crews work safely at height, protect gardens and paths, and leave a finish that holds its colour season after season.</p>
HTML,
                'features' => [
                    'Timber weatherboards, render and brick',
                    'Eaves, fascias, gutters and downpipes',
                    'Window frames, doors and garage doors',
                    'Pressure cleaning and mould treatment',
                    'UV-stable, weather-resistant coating systems',
                    'Height access and full site protection',
                ],
                'sort_order' => 2,
                'is_featured' => true,
                'meta_title' => 'Exterior Painting Melbourne | Lion Painters',
                'meta_description' => 'Exterior house painting in Melbourne with thorough preparation and weather-resistant coating systems. Request a fixed-price quote today.',
            ],
            [
                'title' => 'Surface Preparation & Repairs',
                'slug' => 'surface-preparation-repairs',
                'icon' => 'ruler',
                'tagline' => 'The unseen work that makes it last',
                'image' => 'images/about.svg',
                'excerpt' => 'Sanding, filling, priming and plaster repair — the quiet craft that separates a good paint job from a great one.',
                'description' => <<<'HTML'
<p>The finish you see is only as good as the surface beneath it. Preparation is the stage most painting companies rush, and the one we refuse to compromise.</p>
<p>Our carpenters and painters repair cracks, replace rotten timber, fill dents, sand back peeling coatings and prime every exposed edge. Only then does the topcoat go on.</p>
<h2>Preparation as a craft</h2>
<p>We use dust-controlled sanding, quality fillers and the correct primer for each substrate. The result is a surface that feels smooth to the touch and holds its finish for years, not seasons.</p>
HTML,
                'features' => [
                    'Crack filling and plaster patching',
                    'Timber repair and rotten board replacement',
                    'Dust-controlled sanding and surface smoothing',
                    'Stain blocking and moisture sealing',
                    'Correct primer selection for every substrate',
                    'Detailed inspection before topcoats',
                ],
                'sort_order' => 3,
                'is_featured' => true,
                'meta_title' => 'Surface Preparation & Repairs Melbourne | Lion Painters',
                'meta_description' => 'Professional surface preparation, plaster repair and priming across Melbourne. The foundation of a long-lasting, flawless paint finish.',
            ],
            [
                'title' => 'Deck & Fence Restoration',
                'slug' => 'deck-fence-restoration',
                'icon' => 'layers',
                'tagline' => 'Bring timber back to life',
                'image' => 'images/project-port-melbourne.svg',
                'excerpt' => 'Cleaning, sanding, repairs and protective oils that restore weathered decks and fences to their original warmth.',
                'description' => <<<'HTML'
<p>Timber is generous but unforgiving. Left unprotected, decks grey, splinter and lose their structure — yet most timber can be brought back with the right sequence of care.</p>
<p>We strip back tired coatings, sand to a consistent grain, replace damaged boards and apply a penetrating oil or film-forming finish suited to how the space is used.</p>
<h2>Ready for the seasons</h2>
<p>Slip resistance, UV protection and easy maintenance all factor into our recommendation. The result is a deck that feels good underfoot and a fence that frames the garden beautifully.</p>
HTML,
                'features' => [
                    'Deck and fence cleaning and stripping',
                    'Sanding and splinter removal',
                    'Board replacement and structural repairs',
                    'Penetrating oils and protective stains',
                    'Slip-resistant finish options',
                    'Seasonal maintenance advice',
                ],
                'sort_order' => 4,
                'is_featured' => true,
                'meta_title' => 'Deck & Fence Restoration Melbourne | Lion Painters',
                'meta_description' => 'Restore weathered timber decks and fences with professional cleaning, sanding, repairs and protective finishes across Melbourne.',
            ],
            [
                'title' => 'Water Damage & Patch Repairs',
                'slug' => 'water-damage-patch-repairs',
                'icon' => 'drop',
                'tagline' => 'Calm after the storm',
                'image' => 'images/blog-weather.svg',
                'excerpt' => 'Staining, bubbling and damaged plaster repaired at the source, then blended seamlessly into your existing finish.',
                'description' => <<<'HTML'
<p>Water marks tell a story — and sometimes it is still being written. Before we paint, we identify whether the moisture source has been resolved, then treat staining, bubbling and compromised plaster properly.</p>
<p>Our repair crews remove damaged material, dry and seal the substrate, apply stain-blocking primers and rebuild the surface so the repair disappears into the surrounding wall or ceiling.</p>
<h2>A seamless result</h2>
<p>We match sheen, texture and colour as closely as possible, and where a perfect blend is not achievable we will advise on repainting the full plane for a uniform finish.</p>
HTML,
                'features' => [
                    'Water stain treatment and blocking',
                    'Bubbled and flaking paint removal',
                    'Plaster and cornice repair',
                    'Moisture assessment and sealing',
                    'Colour and sheen matching',
                    'Ceiling and wall blending',
                ],
                'sort_order' => 5,
                'is_featured' => true,
                'meta_title' => 'Water Damage & Patch Repairs Melbourne | Lion Painters',
                'meta_description' => 'Water stain treatment and seamless patch repairs for Melbourne homes. We repair the cause, restore the surface and match the finish.',
            ],
            [
                'title' => 'Residential & Commercial Projects',
                'slug' => 'residential-commercial-projects',
                'icon' => 'building',
                'tagline' => 'One standard, every scale',
                'image' => 'images/project-commercial.svg',
                'excerpt' => 'Complete painting programmes for homes, offices, retail and strata — programmed around how your space needs to operate.',
                'description' => <<<'HTML'
<p>From a single apartment to a multi-level office fit-out, the same standard applies: clear scope, honest programming and a finish that reflects well on everyone involved.</p>
<p>We work with homeowners, architects, builders and property managers, coordinating access, after-hours work and staged handovers so business continues and daily life stays comfortable.</p>
<h2>Programmed around you</h2>
<p>Commercial teams receive a single point of contact, documented colour schedules, site-specific safety plans and photographic progress updates. Residential clients receive the same care at a more personal scale.</p>
HTML,
                'features' => [
                    'Homes, apartments and townhouses',
                    'Offices, retail and hospitality spaces',
                    'Strata and common property programmes',
                    'After-hours and staged works',
                    'Colour schedules and documentation',
                    'Single point of contact and progress reporting',
                ],
                'sort_order' => 6,
                'is_featured' => true,
                'meta_title' => 'Residential & Commercial Painting Melbourne | Lion Painters',
                'meta_description' => 'Residential and commercial painting contractors in Melbourne. Programmed, documented and finished to one exacting standard.',
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => $service['slug']],
                $service + ['is_active' => true]
            );
        }
    }

    private function projects(): void
    {
        $projects = [
            [
                'title' => 'The Toorak Residence',
                'slug' => 'toorak-residence',
                'category' => 'Residential',
                'suburb' => 'Toorak',
                'year' => '2025',
                'colour' => 'Dulux Snowy Mountains · Still',
                'cover_image' => 'images/project-toorak.svg',
                'gallery' => ['images/project-south-yarra.svg', 'images/after.svg'],
                'summary' => 'A full interior repaint of a 1930s residence, layering warm whites and a deep green sitting room for a sense of quiet grandeur.',
                'description' => <<<'HTML'
<p>This Toorak home had beautiful bones — high ceilings, generous windows and original cornices — but decades of neutral paint had flattened its character. Our brief was to bring warmth back without losing the calm.</p>
<p>We repaired hairline cracks throughout the formal rooms, rebuilt the cornice detail in the hallway and specified Dulux Snowy Mountains for the main living spaces. The sitting room received a deeper green in a low-sheen finish, wrapping the room in colour while keeping the light.</p>
<h2>Details that matter</h2>
<p>All trim was hand-cut in a soft white satin, and the original timber doors were stripped, repaired and refinished. The result is a home that feels considered from every angle.</p>
HTML,
                'sort_order' => 1,
                'is_featured' => true,
            ],
            [
                'title' => 'Brighton Coastal Home',
                'slug' => 'brighton-coastal-home',
                'category' => 'Residential',
                'suburb' => 'Brighton',
                'year' => '2024',
                'colour' => 'Haymes Greyology 1 · Natural White',
                'cover_image' => 'images/project-brighton.svg',
                'gallery' => ['images/hero.svg', 'images/blog-sheen.svg'],
                'summary' => 'A light-filled family home refreshed with soft coastal greys, durable washable finishes and a carefully staged four-week programme.',
                'description' => <<<'HTML'
<p>With two young children and a dog, this Brighton family needed a finish that could handle real life. We selected washable low-sheen paints in soft coastal greys, keeping the palette light and the maintenance simple.</p>
<p>Work was staged around the family’s routine: living areas first, bedrooms while they were away, and the kitchen last. Every evening the site was cleaned and furniture returned so the home remained livable.</p>
<h2>Built for family life</h2>
<p>High-traffic walls received an additional coat for durability, and all window frames were sanded and repainted in a hard-wearing enamel. The result is bright, calm and ready for the next decade.</p>
HTML,
                'sort_order' => 2,
                'is_featured' => true,
            ],
            [
                'title' => 'Fitzroy Terrace Revival',
                'slug' => 'fitzroy-terrace-revival',
                'category' => 'Heritage',
                'suburb' => 'Fitzroy',
                'year' => '2024',
                'colour' => 'Dulux Ochre Revival · Antique White',
                'cover_image' => 'images/project-fitzroy.svg',
                'gallery' => ['images/before.svg', 'images/project-toorak.svg'],
                'summary' => 'A heritage terrace brought back to life with period-correct colours, detailed cornice repair and a warm ochre hallway.',
                'description' => <<<'HTML'
<p>Fitzroy terraces carry a particular charm, and this one had survived more than a century of changes. Our task was to undo the most recent ones and return the interior to something closer to its original spirit.</p>
<p>The hallway was painted in a warm ochre chosen from heritage palettes, with all cornices, arches and ceiling roses repaired by hand before painting. Modern white trim was replaced with a softer antique white.</p>
<h2>Respect for the original</h2>
<p>We worked closely with the owners and a heritage colour consultant, testing samples in morning and afternoon light before committing. The finished home feels layered, warm and unmistakably of its time.</p>
HTML,
                'sort_order' => 3,
                'is_featured' => true,
            ],
            [
                'title' => 'South Yarra Apartment',
                'slug' => 'south-yarra-apartment',
                'category' => 'Residential',
                'suburb' => 'South Yarra',
                'year' => '2025',
                'colour' => 'Dulux White Duck · Lexicon Quarter',
                'cover_image' => 'images/project-south-yarra.svg',
                'gallery' => ['images/project-brighton.svg', 'images/about.svg'],
                'summary' => 'A compact city apartment transformed with a tonal white palette, precise cut lines and a flawless satin finish on all joinery.',
                'description' => <<<'HTML'
<p>Small spaces reward precision. In this South Yarra apartment, every edge, shadow line and joinery detail is visible, so the preparation and cutting had to be exact.</p>
<p>We used a tonal palette of warm whites to soften the northern light, with a slightly deeper tone on the ceiling to add intimacy. All built-in joinery was sprayed in a hard-wearing satin for a factory-smooth result.</p>
<h2>Quiet precision</h2>
<p>Working in an occupied building required careful scheduling, lift protection and daily clean-up. The owners returned to an apartment that felt larger, warmer and completely resolved.</p>
HTML,
                'sort_order' => 4,
                'is_featured' => true,
            ],
            [
                'title' => 'Hawthorn Facade Restoration',
                'slug' => 'hawthorn-facade-restoration',
                'category' => 'Exterior',
                'suburb' => 'Hawthorn',
                'year' => '2024',
                'colour' => 'Dulux Vivid White · Black Caviar',
                'cover_image' => 'images/project-hawthorn.svg',
                'gallery' => ['images/about.svg', 'images/blog-weather.svg'],
                'summary' => 'A complete exterior restoration — weatherboards repaired, render patched and the facade repainted in classic black and white.',
                'description' => <<<'HTML'
<p>Years of sun and rain had left this Hawthorn facade chalky, cracked and patchy. The owners wanted a crisp, classic result that would also protect the timber for the long term.</p>
<p>We pressure cleaned the full elevation, replaced rotten weatherboards, patched cracked render and primed every bare edge. Two coats of a UV-stable acrylic were applied to the boards, with a durable gloss enamel on the trims.</p>
<h2>Protection and presence</h2>
<p>The black-and-white scheme sharpens the architecture and sits comfortably among the neighbouring period homes. Gutters, fascias and window frames were all included for a complete result.</p>
HTML,
                'sort_order' => 5,
                'is_featured' => true,
            ],
            [
                'title' => 'Port Melbourne Deck & Garden Room',
                'slug' => 'port-melbourne-deck-restoration',
                'category' => 'Exterior',
                'suburb' => 'Port Melbourne',
                'year' => '2025',
                'colour' => 'Intergrain Natural Stain · Charcoal',
                'cover_image' => 'images/project-port-melbourne.svg',
                'gallery' => ['images/blog-weather.svg', 'images/about.svg'],
                'summary' => 'A weathered entertainer’s deck and surrounding fence restored with deep sanding, board replacement and a natural protective oil.',
                'description' => <<<'HTML'
<p>This Port Melbourne deck had entertained a decade of summers, but the timber was grey, splintered and lifting in places. The fence had lost most of its original stain.</p>
<p>We stripped the old coating, sanded the full deck to a consistent grain, replaced twelve damaged boards and reinforced loose fixings. A penetrating natural oil was chosen to keep the timber’s warmth while improving slip resistance.</p>
<h2>Ready to entertain again</h2>
<p>The surrounding fence was cleaned and finished in a charcoal stain, framing the garden and tying the outdoor room together. The owners now have a space that feels new without losing its lived-in character.</p>
HTML,
                'sort_order' => 6,
                'is_featured' => true,
            ],
            [
                'title' => 'Cremorne Studio Fit-out',
                'slug' => 'cremorne-studio-fit-out',
                'category' => 'Commercial',
                'suburb' => 'Cremorne',
                'year' => '2025',
                'colour' => 'Dulux Monument · Vivid White',
                'cover_image' => 'images/project-commercial.svg',
                'gallery' => ['images/project-fitzroy.svg', 'images/blog-sheen.svg'],
                'summary' => 'A working design studio repainted over two weekends, with a moody feature wall, acoustic ceiling refresh and zero downtime.',
                'description' => <<<'HTML'
<p>A busy Cremorne design studio needed a refresh without disrupting its team or clients. We programmed the work across two weekends, with all preparation and spraying completed outside business hours.</p>
<p>The reception and meeting room received a deep Monument feature wall, while the open studio was lifted with Vivid White and a fresh coat on the exposed ceiling. All data and electrical points were carefully protected and reinstated.</p>
<h2>Zero downtime</h2>
<p>Furniture was moved and returned each morning, and the team walked into a clean, finished space every Monday. The project was completed on schedule and within the fixed price.</p>
HTML,
                'sort_order' => 7,
                'is_featured' => true,
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['slug' => $project['slug']],
                $project + ['is_published' => true]
            );
        }
    }

    private function testimonials(): void
    {
        $testimonials = [
            [
                'name' => 'Sarah Mitchell',
                'suburb' => 'Toorak',
                'project_type' => 'Full interior repaint',
                'rating' => 5,
                'quote' => 'The team treated our home as if it were their own. Every edge is perfect, and the colour advice completely changed how the living room feels. We would not use anyone else.',
                'sort_order' => 1,
            ],
            [
                'name' => 'James and Priya Anand',
                'suburb' => 'Brighton',
                'project_type' => 'Interior & exterior',
                'rating' => 5,
                'quote' => 'Professional, punctual and genuinely lovely to have around. They staged the work around our children and left the house spotless every single day.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Eleanor Hart',
                'suburb' => 'Fitzroy',
                'project_type' => 'Heritage restoration',
                'rating' => 5,
                'quote' => 'They understood the history of the terrace immediately and were meticulous with the cornices. The ochre hallway is the first thing every visitor comments on.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Marcus Lee',
                'suburb' => 'South Yarra',
                'project_type' => 'Apartment repaint',
                'rating' => 5,
                'quote' => 'Impeccable finish on the joinery and not a single mark on the floors. The quote was detailed, the timeline was accurate and the result exceeded expectations.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Olivia Bennett',
                'suburb' => 'Hawthorn',
                'project_type' => 'Exterior restoration',
                'rating' => 5,
                'quote' => 'Our facade looks better than it has in twenty years. They repaired timber we did not even know was damaged and explained every step along the way.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Daniel Wu',
                'suburb' => 'Cremorne',
                'project_type' => 'Commercial fit-out',
                'rating' => 5,
                'quote' => 'Two weekends, zero disruption and a studio that finally reflects the quality of our work. The documentation and communication were outstanding.',
                'sort_order' => 6,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                ['name' => $testimonial['name']],
                $testimonial + ['is_published' => true]
            );
        }
    }

    private function posts(): void
    {
        $posts = [
            [
                'title' => 'How to Choose the Right Paint Sheen for Every Room',
                'slug' => 'choosing-the-right-paint-sheen',
                'category' => 'Advice',
                'cover_image' => 'images/blog-sheen.svg',
                'excerpt' => 'Matte, low-sheen, satin or gloss? A practical guide to choosing a finish that looks beautiful and suits the way each room is used.',
                'reading_time' => 5,
                'published_at' => now()->subDays(6),
                'meta_title' => 'How to Choose the Right Paint Sheen | Lion Painters',
                'meta_description' => 'A room-by-room guide to paint sheen, from matte living rooms to durable satin joinery, by Melbourne’s interior painting specialists.',
                'body' => <<<'HTML'
<p>Sheen is one of the most overlooked decisions in a repaint, yet it changes how colour reads, how a surface wears and how easily a room can be cleaned. Here is how we specify finishes for Melbourne homes.</p>
<h2>Matte and ultra-matte</h2>
<p>Matte finishes absorb light, soften imperfections and give walls a deep, velvety appearance. They are ideal for low-traffic living rooms, studies and bedrooms. The trade-off is lower scrub resistance, so we recommend a washable matte rather than a traditional flat finish.</p>
<h2>Low-sheen</h2>
<p>Our most specified finish. Low-sheen sits between matte and satin, offering a subtle reflection with enough durability for hallways and family rooms. It touches up well and hides minor surface variation.</p>
<h2>Satin and semi-gloss</h2>
<p>Satin is the practical choice for kitchens, bathrooms, children’s rooms and joinery. It resists moisture and marks, and cleans easily. Semi-gloss and gloss remain the right choice for trims, doors and period details where a crisp, reflective edge is desired.</p>
<h2>A simple rule</h2>
<p>Choose the lowest sheen the room can tolerate, then step up one level in high-use areas. If you are unsure, ask us to prepare sample boards in your actual light before the project begins.</p>
HTML,
                'is_published' => true,
            ],
            [
                'title' => 'A Considered Palette: Colour Trends That Will Last',
                'slug' => 'considered-palette-colour-trends',
                'category' => 'Colour',
                'cover_image' => 'images/blog-palette.svg',
                'excerpt' => 'Trends come and go. These are the palettes our clients still love years after the final coat — and why they work in Melbourne light.',
                'reading_time' => 4,
                'published_at' => now()->subDays(21),
                'meta_title' => 'Timeless Paint Colour Trends for Melbourne Homes | Lion Painters',
                'meta_description' => 'Explore warm neutrals, deep greens and heritage tones that stand the test of time in Melbourne homes, from the Lion Painters colour studio.',
                'body' => <<<'HTML'
<p>Every season brings a new colour of the year, but the palettes that endure tend to share a few quiet qualities: warmth, restraint and a relationship to natural light.</p>
<h2>Warm neutrals</h2>
<p>Whites with a touch of stone or ochre flatter Melbourne’s changeable light far better than cool blue-whites. They create a calm backdrop for timber, linen and art without feeling sterile.</p>
<h2>Deep greens and earthy reds</h2>
<p>A single deep room — a study, sitting room or hallway — adds depth to an otherwise light home. Greens drawn from eucalyptus and olive, and reds grounded in terracotta, feel connected to the landscape rather than to a passing trend.</p>
<h2>Heritage tones, used sparingly</h2>
<p>Ochre, muted blue and antique cream suit period architecture, but the trick is restraint. Use them on trim, a hallway or a single wall, and keep adjoining rooms quiet.</p>
<h2>Test in your own light</h2>
<p>No photograph can replicate the way colour behaves in your home. We always recommend large sample boards viewed at different times of day before committing to a scheme.</p>
HTML,
                'is_published' => true,
            ],
            [
                'title' => 'Preparing Your Home for an Exterior Repaint',
                'slug' => 'preparing-your-home-for-exterior-painting',
                'category' => 'Process',
                'cover_image' => 'images/blog-weather.svg',
                'excerpt' => 'What to expect before, during and after an exterior repaint — and the small preparations that make a big difference to the result.',
                'reading_time' => 6,
                'published_at' => now()->subDays(40),
                'meta_title' => 'Preparing for an Exterior Repaint | Lion Painters Melbourne',
                'meta_description' => 'A practical guide to preparing your home for exterior painting, including access, plants, weather and what a professional crew handles for you.',
                'body' => <<<'HTML'
<p>An exterior repaint is a significant investment, and a little preparation helps the project run smoothly. Here is what we ask homeowners to consider before our crew arrives.</p>
<h2>Access and parking</h2>
<p>We need room for ladders, scaffolding and a vehicle close to the property. Please let us know about shared driveways, permit parking or body corporate requirements in advance.</p>
<h2>Gardens and outdoor furniture</h2>
<p>Move furniture, pots and delicate plants away from the walls where possible. We protect gardens with drop sheets and mask sensitive surfaces, but anything you can relocate makes the site safer and faster.</p>
<h2>Windows, doors and pets</h2>
<p>Some windows and doors will be temporarily sealed while we work on adjacent elevations. We will agree a daily schedule with you, including safe areas for pets and access in and out of the house.</p>
<h2>Weather and programming</h2>
<p>Exterior coatings need suitable conditions to cure. We monitor forecasts daily and build flexibility into the programme. If rain interrupts, we protect completed work and resume as soon as surfaces are dry.</p>
<h2>The final walkthrough</h2>
<p>At handover we walk the full elevation with you, note any touch-ups and provide care instructions. Our 10-year workmanship guarantee then applies to the preparation and application we have completed.</p>
HTML,
                'is_published' => true,
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(['slug' => $post['slug']], $post);
        }
    }

    private function faqs(): void
    {
        $faqs = [
            [
                'question' => 'How much does interior painting cost in Melbourne?',
                'answer' => 'Every project is different, but most full interior repaints fall between $6,000 and $18,000 depending on size, surface condition and the level of preparation required. We provide a detailed fixed-price quote after a site visit, with no hourly surprises.',
                'sort_order' => 1,
            ],
            [
                'question' => 'How long will my project take?',
                'answer' => 'A single room typically takes two to three days. A full three-bedroom home usually takes one to two weeks, including preparation and drying time. We confirm a realistic schedule in your quote and update you daily.',
                'sort_order' => 2,
            ],
            [
                'question' => 'Do you help with colour selection?',
                'answer' => 'Yes. Colour consultation is included in every interior project. We discuss light, furnishings and the mood you want, prepare sample boards and view them in your home at different times of day before finalising the palette.',
                'sort_order' => 3,
            ],
            [
                'question' => 'Are your paints safe for children and pets?',
                'answer' => 'We specify low-VOC, water-based paints wherever possible and ventilate the home throughout. Most rooms can be used again the same evening. For sensitive households we can recommend our lowest-emission coating options.',
                'sort_order' => 4,
            ],
            [
                'question' => 'Do I need to move out during the work?',
                'answer' => 'Rarely. We stage the work room by room, protect furniture and clean up daily. Most clients remain at home throughout. If a full-house project is easier to complete while you are away, we are happy to coordinate access.',
                'sort_order' => 5,
            ],
            [
                'question' => 'What does your guarantee cover?',
                'answer' => 'Our 10-year workmanship guarantee covers preparation and application defects, including peeling, blistering or premature failure caused by our work. It does not cover damage from leaks, structural movement or accidental impact.',
                'sort_order' => 6,
            ],
            [
                'question' => 'Do you work outside Melbourne?',
                'answer' => 'We are based in Cremorne and work across metropolitan Melbourne, including the inner east, bayside and northern suburbs. For larger projects we are happy to discuss locations further afield.',
                'sort_order' => 7,
            ],
            [
                'question' => 'Are you insured?',
                'answer' => 'Yes. We hold $20 million public liability insurance and full WorkCover for every team member. Certificates are available on request before work begins.',
                'sort_order' => 8,
            ],
            [
                'question' => 'How do I get a quote?',
                'answer' => 'Request a quote through our website or call us directly. We will arrange a site visit, discuss your scope and colour direction, and send a detailed fixed-price proposal — usually within one business day of the visit.',
                'sort_order' => 9,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq + ['is_published' => true]
            );
        }
    }

    private function settings(): void
    {
        foreach (config('site.defaults') as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    private function enquiries(): void
    {
        $quotes = [
            [
                'name' => 'Claire Donovan',
                'email' => 'claire.donovan@example.com',
                'phone' => '0412 884 201',
                'suburb' => 'Malvern',
                'service' => 'Interior Painting',
                'property_type' => 'House',
                'bedrooms' => '4',
                'timeline' => 'Within 1–3 months',
                'details' => 'We are repainting the living areas, hallway and three bedrooms before moving in. The walls are in good condition but there are some old picture hooks and minor cracks to repair.',
                'status' => 'new',
                'created_at' => now()->subHours(20),
            ],
            [
                'name' => 'Andrew Patel',
                'email' => 'andrew.patel@example.com',
                'phone' => '0403 221 908',
                'suburb' => 'Kew',
                'service' => 'Exterior Painting',
                'property_type' => 'House',
                'bedrooms' => '3',
                'timeline' => 'As soon as possible',
                'details' => 'Weatherboards at the front of the house are peeling and the render around the garage has cracked. Looking for a full exterior repaint including eaves and trims.',
                'status' => 'contacted',
                'admin_notes' => 'Site visit booked for Thursday at 9am.',
                'created_at' => now()->subDays(3),
            ],
            [
                'name' => 'Nadia Rossi',
                'email' => 'nadia.rossi@example.com',
                'phone' => '0455 660 132',
                'suburb' => 'Port Melbourne',
                'service' => 'Deck & Fence Restoration',
                'property_type' => 'Townhouse',
                'bedrooms' => '2',
                'timeline' => 'Within 1–3 months',
                'details' => 'Deck is approximately 30 square metres and quite grey. Fence is about 18 metres. Would like a natural look rather than a painted finish.',
                'status' => 'quoted',
                'admin_notes' => 'Quote sent 12 March. Follow up next week.',
                'created_at' => now()->subDays(8),
            ],
        ];

        foreach ($quotes as $quote) {
            QuoteRequest::create($quote);
        }

        ContactMessage::create([
            'name' => 'Hannah Wells',
            'email' => 'hannah.wells@example.com',
            'phone' => '0421 550 318',
            'suburb' => 'Armadale',
            'subject' => 'Question about colour consultation',
            'message' => "Hello,\n\nWe are planning to repaint our living room and dining room later this year. Do you offer a stand-alone colour consultation, or is it only included with a full painting project?\n\nThank you,\nHannah",
            'is_read' => false,
            'created_at' => now()->subHours(5),
        ]);

        ContactMessage::create([
            'name' => 'Peter Nguyen',
            'email' => 'peter.nguyen@example.com',
            'phone' => '0433 712 004',
            'suburb' => 'Glen Iris',
            'subject' => 'Water stain on ceiling',
            'message' => "Hi there,\n\nWe had a roof leak repaired last month and now have a brown water stain on the bedroom ceiling. The plaster feels solid. Can you quote to seal and repaint the ceiling so it blends in?\n\nKind regards,\nPeter",
            'is_read' => true,
            'created_at' => now()->subDays(2),
        ]);
    }
}
