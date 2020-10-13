
## Dump info in terminal
    
Use `dump()` function and:

    bin/console server:dump

=>
    
    GET http://localhost:8080/questions/how-to-tie-my-shoes-with-magics
    -------------------------------------------------------------------
    
     ------------ ------------------------------------------- 
      date         Fri, 24 Jul 2020 14:34:23 +0200            
      controller   "App\Controller\QuestionController::show"  
      source       QuestionController.php on line 20          
      file         src/Controller/QuestionController.php      
     ------------ ------------------------------------------- 

"how-to-tie-my-shoes-with-magics"

## Router

    php bin/console debug:router

    php bin/console router:match /comments/10/vote/up --method=POST
    
    php bin/console debug:autowiring log
