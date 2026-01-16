<?php
// search_courses.php
session_start();

// Include or define the courses array
$all_courses = [
    // Semester 1
    ['slug' => 'differential-calculus', 'title' => 'Differential Calculus & Co-ordinate Geometry',
    'nicknames' => ['mat1','mat1']],
    ['slug' => 'physics-1', 'title' => 'Physics 1'],
    ['slug' => 'physics-1-lab', 'title' => 'Physics 1 Lab'],
    ['slug' => 'english-reading', 'title' => 'English Reading Skills & Public Speaking'],
    ['slug' => 'intro-computer-studies', 'title' => 'Introduction to Computer Studies'],
    ['slug' => 'intro-programming', 'title' => 'Introduction to Programming'],
    ['slug' => 'intro-programming-lab', 'title' => 'Introduction to Programming Lab'],
    
    // Semester 2
    ['slug' => 'discrete-mathematics', 'title' => 'Discrete Mathematics'],
    ['slug' => 'integral-calculus', 'title' => 'Integral Calculus & Ordinary Differential Equations'],
    ['slug' => 'object-oriented-programming-1', 'title' => 'Object Oriented Programming 1'],
    ['slug' => 'physics-2', 'title' => 'Physics 2'],
    ['slug' => 'physics-2-lab', 'title' => 'Physics 2 Lab'],
   [
        'slug' => 'english-writing', 
        'title' => 'English Writing Skills & Communications',
        'nicknames' => ['english 2', 'eng 2', 'technical writing']
    ],
    ['slug' => 'electrical-circuits', 'title' => 'Introduction to Electrical Circuits'],
    ['slug' => 'electrical-circuits-lab', 'title' => 'Introduction to Electrical Circuits Lab'],
    
    // Semester 3
    ['slug' => 'chemistry', 'title' => 'Chemistry'],
    ['slug' => 'complex-variable', 'title' => 'Complex Variable, Laplace & Z-Transformation'],
    ['slug' => 'introduction-database', 'title' => 'Introduction to Database'],
    ['slug' => 'electronic-devices-lab', 'title' => 'Electronic Devices Lab'],
    ['slug' => 'principles-accounting', 'title' => 'Principles of Accounting'],
    ['slug' => 'electronic-devices', 'title' => 'Electronic Devices'],
    ['slug' => 'data-structures', 'title' => 'Data Structure'],
    ['slug' => 'data-structures-lab', 'title' => 'Data Structure Lab'],
    ['slug' => 'computer-aided-design', 'title' => 'Computer Aided Design & Drafting'],
    
    // Semester 4
    ['slug' => 'algorithms', 'title' => 'Algorithms'],
    ['slug' => 'matrices-vectors', 'title' => 'Matrices, Vectors, Fourier Analysis'],
    ['slug' => 'object-oriented-programming-2', 'title' => 'Object Oriented Programming 2'],
    ['slug' => 'object-oriented-analysis', 'title' => 'Object Oriented Analysis and Design'],
    ['slug' => 'bangladesh-studies', 'title' => 'Bangladesh Studies'],
    ['slug' => 'digital-logic', 'title' => 'Digital Logic and Circuits'],
    ['slug' => 'digital-logic-lab', 'title' => 'Digital Logic and Circuits Lab'],
    ['slug' => 'computational-statistics', 'title' => 'Computational Statistics and Probability'],
    
    // Semester 5
    ['slug' => 'theory-computation', 'title' => 'Theory of Computation'],
    ['slug' => 'principles-economics', 'title' => 'Principles of Economics'],
    ['slug' => 'business-communication', 'title' => 'Business Communication'],
    ['slug' => 'numerical-methods', 'title' => 'Numerical Methods for Science and Engineering'],
    ['slug' => 'data-communication', 'title' => 'Data Communication'],
    ['slug' => 'microprocessor', 'title' => 'Microprocessor and Embedded Systems'],
    ['slug' => 'software-engineering', 'title' => 'Software Engineering'],
    
    // Semester 6
    ['slug' => 'artificial-intelligence', 'title' => 'Artificial Intelligence and Expert System'],
    ['slug' => 'computer-networks', 'title' => 'Computer Networks'],
    ['slug' => 'computer-organization', 'title' => 'Computer Organization and Architecture'],
    ['slug' => 'operating-system', 'title' => 'Operating System'],
    ['slug' => 'web-technologies', 'title' => 'Web Technologies'],
    ['slug' => 'engineering-ethics', 'title' => 'Engineering Ethics'],
    ['slug' => 'compiler-design', 'title' => 'Compiler Design'],

];
    
        
    
   
    

// Define course descriptions
$course_descriptions = [
    // Semester 1
    'Differential Calculus & Co-ordinate Geometry' => 'Learn derivatives, limits, and analytical geometry for engineering applications',
    'Physics 1' => 'Fundamental principles of mechanics, thermodynamics, and wave motion',
    'Physics 1 Lab' => 'Hands-on experiments in mechanics and thermodynamics',
    'English Reading Skills & Public Speaking' => 'Develop communication skills and presentation techniques',
    'Introduction to Computer Studies' => 'Overview of computer systems and their applications',
    'Introduction to Programming' => 'Basic programming concepts using C/C++ language',
    'Introduction to Programming Lab' => 'Practical programming exercises and problem solving',
    
    // Semester 2
    'Discrete Mathematics' => 'Mathematical structures for computer science applications',
    'Integral Calculus & Ordinary Differential Equations' => 'Integration techniques and solving differential equations',
    'Object Oriented Programming 1' => 'Learn OOP concepts with Java programming language',
    'Physics 2' => 'Electricity, magnetism, and modern physics concepts',
    'Physics 2 Lab' => 'Experiments in electricity, magnetism, and optics',
    'English Writing Skills & Communications' => 'Technical writing and professional communication',
    'Introduction to Electrical Circuits' => 'Basic circuit theory, laws, and analysis techniques',
    'Introduction to Electrical Circuits Lab' => 'Practical circuit design and measurement',
    
    // Semester 3
    'Chemistry' => 'Chemical principles and reactions for engineering applications',
    'Complex Variable, Laplace & Z-Transformation' => 'Complex analysis and transform methods',
    'Introduction to Database' => 'Database concepts, SQL, and relational database design',
    'Electronic Devices Lab' => 'Practical experiments with semiconductor devices',
    'Principles of Accounting' => 'Basic accounting principles and financial statements',
    'Electronic Devices' => 'Semiconductor devices, diodes, transistors, and amplifiers',
    'Data Structure' => 'Study of arrays, linked lists, stacks, queues, and trees',
    'Data Structure Lab' => 'Implementation and analysis of data structures',
    'Computer Aided Design & Drafting' => 'CAD tools for engineering design and modeling',
    
    // Semester 4
    'Algorithms' => 'Design and analysis of efficient algorithms for problem solving',
    'Matrices, Vectors, Fourier Analysis' => 'Linear algebra and Fourier analysis techniques',
    'Object Oriented Programming 2' => 'Advanced OOP concepts and software development',
    'Object Oriented Analysis and Design' => 'Software design patterns and UML modeling',
    'Bangladesh Studies' => 'History, culture, and development of Bangladesh',
    'Digital Logic and Circuits' => 'Boolean algebra, logic gates, and digital circuit design',
    'Digital Logic and Circuits Lab' => 'Practical digital circuit implementation',
    'Computational Statistics and Probability' => 'Statistical methods and probability theory for data analysis',
    
    // Semester 5
    'Theory of Computation' => 'Formal languages, automata theory, and computational complexity',
    'Principles of Economics' => 'Micro and macro economic principles',
    'Business Communication' => 'Professional communication in business context',
    'Numerical Methods for Science and Engineering' => 'Numerical algorithms for scientific computing',
    'Data Communication' => 'Network protocols, transmission media, and communication systems',
    'Microprocessor and Embedded Systems' => 'Microprocessor architecture and embedded system design',
    'Software Engineering' => 'Software development lifecycle, methodologies, and project management',
    
    // Semester 6
    'Artificial Intelligence and Expert System' => 'AI algorithms, knowledge representation, and expert systems',
    'Computer Networks' => 'Network architectures, protocols, and internet technologies',
    'Computer Organization and Architecture' => 'Computer hardware design and system architecture',
    'Operating System' => 'Process management, memory management, and file systems',
    'Web Technologies' => 'HTML, CSS, JavaScript, and server-side programming',
    'Engineering Ethics' => 'Professional ethics and social responsibility for engineers',
    'Compiler Design' => 'Compiler construction techniques and optimization',
    
    
];

$course_descriptions = [
    // Semester 1
    'Differential Calculus & Co-ordinate Geometry' => 'Learn derivatives, limits, and analytical geometry for engineering applications',
    'Physics 1' => 'Fundamental principles of mechanics, thermodynamics, and wave motion',
    'Physics 1 Lab' => 'Hands-on experiments in mechanics and thermodynamics',
    'English Reading Skills & Public Speaking' => 'Develop communication skills and presentation techniques',
    'Introduction to Computer Studies' => 'Overview of computer systems and their applications',
    'Introduction to Programming' => 'Basic programming concepts using C/C++ language',
    'Introduction to Programming Lab' => 'Practical programming exercises and problem solving',
    
    // Semester 2
    'Discrete Mathematics' => 'Mathematical structures for computer science applications',
    'Integral Calculus & Ordinary Differential Equations' => 'Integration techniques and solving differential equations',
    'Object Oriented Programming 1' => 'Learn OOP concepts with Java programming language',
    'Physics 2' => 'Electricity, magnetism, and modern physics concepts',
    'Physics 2 Lab' => 'Experiments in electricity, magnetism, and optics',
    'English Writing Skills & Communications' => 'Technical writing and professional communication',
    'Introduction to Electrical Circuits' => 'Basic circuit theory, laws, and analysis techniques',
    'Introduction to Electrical Circuits Lab' => 'Practical circuit design and measurement',
    
    // Semester 3
    'Chemistry' => 'Chemical principles and reactions for engineering applications',
    'Complex Variable, Laplace & Z-Transformation' => 'Complex analysis and transform methods',
    'Introduction to Database' => 'Database concepts, SQL, and relational database design',
    'Electronic Devices Lab' => 'Practical experiments with semiconductor devices',
    'Principles of Accounting' => 'Basic accounting principles and financial statements',
    'Electronic Devices' => 'Semiconductor devices, diodes, transistors, and amplifiers',
    'Data Structure' => 'Study of arrays, linked lists, stacks, queues, and trees',
    'Data Structure Lab' => 'Implementation and analysis of data structures',
    'Computer Aided Design & Drafting' => 'CAD tools for engineering design and modeling',
    
    // Semester 4
    'Algorithms' => 'Design and analysis of efficient algorithms for problem solving',
    'Matrices, Vectors, Fourier Analysis' => 'Linear algebra and Fourier analysis techniques',
    'Object Oriented Programming 2' => 'Advanced OOP concepts and software development',
    'Object Oriented Analysis and Design' => 'Software design patterns and UML modeling',
    'Bangladesh Studies' => 'History, culture, and development of Bangladesh',
    'Digital Logic and Circuits' => 'Boolean algebra, logic gates, and digital circuit design',
    'Digital Logic and Circuits Lab' => 'Practical digital circuit implementation',
    'Computational Statistics and Probability' => 'Statistical methods and probability theory for data analysis',
    
    // Semester 5
    'Theory of Computation' => 'Formal languages, automata theory, and computational complexity',
    'Principles of Economics' => 'Micro and macro economic principles',
    'Business Communication' => 'Professional communication in business context',
    'Numerical Methods for Science and Engineering' => 'Numerical algorithms for scientific computing',
    'Data Communication' => 'Network protocols, transmission media, and communication systems',
    'Microprocessor and Embedded Systems' => 'Microprocessor architecture and embedded system design',
    'Software Engineering' => 'Software development lifecycle, methodologies, and project management',
    
    // Semester 6
    'Artificial Intelligence and Expert System' => 'AI algorithms, knowledge representation, and expert systems',
    'Computer Networks' => 'Network architectures, protocols, and internet technologies',
    'Computer Organization and Architecture' => 'Computer hardware design and system architecture',
    'Operating System' => 'Process management, memory management, and file systems',
    'Web Technologies' => 'HTML, CSS, JavaScript, and server-side programming',
    'Engineering Ethics' => 'Professional ethics and social responsibility for engineers',
    'Compiler Design' => 'Compiler construction techniques and optimization',
    
   
];

// Only process if it's an AJAX search request
if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
    $search_query = trim($_GET['search_query']);
    $search_lower = strtolower($search_query);
    $results = [];
    
    foreach ($all_courses as $course) {
        $title = $course['title'];
        $title_lower = strtolower($title);
        $nicknames = isset($course['nicknames']) ? $course['nicknames'] : [];
        
        $match_type = 0; // 0 = no match, 1 = starts with, 2 = contains
        $match_position = PHP_INT_MAX;
        
        // 1. Check in TITLE (official name)
        if (strpos($title_lower, $search_lower) === 0) {
            $match_type = 1;
            $match_position = 0;
        } elseif (strpos($title_lower, $search_lower) !== false) {
            $match_type = 2;
            $match_position = strpos($title_lower, $search_lower);
        }
        
        // 2. Check in NICKNAMES
        foreach ($nicknames as $nickname) {
            $nickname_lower = strtolower($nickname);
            
            // Check if nickname exactly matches or starts with
            if ($nickname_lower === $search_lower) {
                // Exact nickname match - highest priority
                $match_type = 1;
                $match_position = -1; // Even before title start matches
                break;
            } elseif (strpos($nickname_lower, $search_lower) === 0) {
                // Nickname starts with search
                if ($match_type !== 1 || $match_position > 0) {
                    $match_type = 1;
                    $match_position = 0;
                }
            } elseif (strpos($nickname_lower, $search_lower) !== false) {
                // Nickname contains search
                if ($match_type === 0) {
                    $match_type = 2;
                    $match_position = strpos($nickname_lower, $search_lower);
                }
            }
        }
        
        if ($match_type > 0) {
            $description = $course_descriptions[$title] ?? 'Explore this course to enhance your knowledge in Computer Science and Engineering';
            
            $results[] = [
                'slug' => $course['slug'],
                'title' => $title,
                'description' => substr($description, 0, 100) . '...',
                'match_type' => $match_type,
                'match_position' => $match_position,
                'matched_by' => 'nickname' // Track what matched
            ];
        }
    }
    
    // Sort results:
    // 1. Exact nickname matches first
    // 2. Title starts with match
    // 3. Nickname starts with match  
    // 4. Contains matches
    usort($results, function($a, $b) {
        // Sort by match type (lower is better)
        if ($a['match_position'] === -1 && $b['match_position'] !== -1) return -1;
        if ($b['match_position'] === -1 && $a['match_position'] !== -1) return 1;
        
        if ($a['match_type'] != $b['match_type']) {
            return $a['match_type'] - $b['match_type'];
        }
        
        // Then by position
        if ($a['match_position'] != $b['match_position']) {
            return $a['match_position'] - $b['match_position'];
        }
        
        // Then alphabetically
        return strcmp($a['title'], $b['title']);
    });
    
    // Clean up response
    foreach ($results as &$result) {
        unset($result['match_type']);
        unset($result['match_position']);
        unset($result['matched_by']);
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
}
?>