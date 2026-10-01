<?php
require_once __DIR__ . '/classMember.php';
require_once __DIR__ . '/classTeam.php';
require_once __DIR__ . '/classOpposingClub.php';
require_once __DIR__ . '/classPlayerHasTeam.php';
require_once __DIR__ . '/classMatchFoot.php';

// 1. Création des joueurs 
function createPlayers(): array
{
    return [
        // new TYPE('Firstname', 'Lastname', 'picture.png', new DateTime('YYYY-MM-DD')) Syntaxe pour créer un joueur (Regarder constructeur),
        new Player('Kylian', 'Mbappé', 'mbappe.png', new DateTime('1998-12-20')),
        new Player('Antoine', 'Griezmann', 'griezmann.png', new DateTime('1991-03-21')),
        new Player('Ousmane', 'Dembélé', 'dembele.png', new DateTime('1997-05-15')),
        new Player('Aurelien', 'Tchouaméni', 'tchouameni.png', new DateTime('2000-01-27')),
        new Player('Mike', 'Maignan', 'maignan.png', new DateTime('1995-07-03')),
    ];
}

// 2. Création des équipes 
function createTeams(): array
{
    return [
        new Team('Équipe A (Séniors)'),
        new Team('Équipe B (Réserves)'),
        new Team('Équipe U19'),
        new Team('Équipe U17'),
        new Team('Équipe Féminine'),
    ];
}

// 3. Création des clubs adverses 
// Syntaxe : new Address('Numéro', 'Rue', 'Code Postal', 'Ville')
// Syntaxe OpposingClub : new OpposingClub($objetAddress)

function createOpposingClubs(): array
{
    return [
        new OpposingClub('10', 'Rue du Stade', '59000', 'Lille'),
        new OpposingClub('5', 'Avenue de Lyon', '69000', 'Lyon'),
        new OpposingClub('20', 'Boulevard de Marseille', '13000', 'Marseille'),
        new OpposingClub('15', 'Rue de Bordeaux', '33000', 'Bordeaux'),
        new OpposingClub('8', 'Place de Nantes', '44000', 'Nantes'),
    ];
}

// 4. Création des membres du staff 
function createStaffMembers(): array
{
    return [
        new StaffMember('Didier', 'Deschamps', 'deschamps.png', 'Entraîneur'),
        new StaffMember('Guy', 'Stéphan', 'stephan.png', 'Préparateur'),
        new StaffMember('Franck', 'Raviot', 'raviot.png', 'Entraîneur gardiens'),
        new StaffMember('Cyril', 'Moine', 'moine.png', 'Préparateur physique'),
        new StaffMember('Alexandre', 'Kylian', 'kylian.png', 'Analyste'),
    ];
}

// 5. Création des liaisons. Un joueur reçoit une équipe et un rôle
function createPlayerHasTeams(array $players, array $teams): array
{
    $roles = ['attaquant', 'milieu', 'Défenseur', 'Gardien'];
    $links = [];

    foreach ($players as $index => $player) {
        $team = $teams[$index % count($teams)];
        $role = $roles[$index % count($roles)];
        
        // On passe directement les objets $player et $team instanciés
        $links[] = new PlayerHasTeam($role, $player, $team);
    }

    return $links;
}

// 6. Création des matchs
function createMatches(array $teams, array $opposingClubs): array
{
    $matchesData = [
        [3, 1, '2026-01-15', 'Lille'],
        [2, 2, '2026-02-10', 'Lyon'],
        [1, 0, '2026-03-05', 'Marseille'],
        [0, 2, '2026-04-12', 'Bordeaux'],
        [4, 1, '2026-05-20', 'Nantes'],
    ];

    $matches = [];
    foreach ($matchesData as $index => [$teamScore, $opponentScore, $date, $city]) {
        $team = $teams[$index % count($teams)];
        $opposingClub = $opposingClubs[$index % count($opposingClubs)];

        $matches[] = new MatchFoot(
            $teamScore,
            $opponentScore,
            new DateTime($date),
            $city,
            $team,
            $opposingClub
        );
    }

    return $matches;
}

// --- EXÉCUTION DU JEU D'ESSAIS ---

$players = createPlayers();
$teams = createTeams();
$opposingClubs = createOpposingClubs();
$staffMembers = createStaffMembers();
$playerHasTeams = createPlayerHasTeams($players, $teams);
$matches = createMatches($teams, $opposingClubs);

echo "=== BILAN DES FIXTURES ===" . PHP_EOL;
echo "Joueurs créés : " . count($players) . PHP_EOL;
echo "Équipes créées : " . count($teams) . PHP_EOL;
echo "Clubs adverses créés : " . count($opposingClubs) . PHP_EOL;
echo "Membres du staff créés : " . count($staffMembers) . PHP_EOL;
echo "Liaisons Joueur/Équipe créées : " . count($playerHasTeams) . PHP_EOL;
echo "Matchs enregistrés : " . count($matches) . PHP_EOL;