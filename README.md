# QandA Program  
  
The Best interactive CLI program for Question and Answer practice, _ever!_.  
  
## Features  
- Create a question  
- List all questions  
- Practice  
- Stats  
- Reset  
- Exit  
  
## Config 

Inside your projectdo the following steps:

1. Clonning the repositorio on your own machine.  
`git clone`  

2. Installing packages with composer
`composer update`  
  
3. Creating the `.env` file and config with your own credentials.  
  
4. This program is using Sail to containerize this Laravel Application, so execute the following command.  
`./vendor/bin/sail up` . Be careful with the ports that your are using currently.  
  
5. Migrating tables and so on.  
`./vendor/bin/sail artisan migrate`.  
  
6. Seeding in your database: to create some questions and users.  
`./vendor/bin/sail artisan db:seed`  
  
## How to use  
  
The following instructions will help to run this program.   
  
**1. Start the program**  
  
Basically, there are **2 ways** to interact with it.  
The first way is as a guest, and the second option is as a specific user.  
  
**as a GUEST**.  
`./vendor/bin/sail artisan qanda:interactive`.  
The previous command allows you to interact with CLI program as a guest user.   
      
**or as a SPECIFIC USER**.  
`./vendor/bin/sail artisan qanda:interactive cris@test.com`.  
The previous command allows you to interact with CLI program as a specific user, in this case as "cris@test.com".  
  
After that, you will see the MAIN MENU with **6 options** to choose.  
  
[0] Create a questions
[1]. List all questions
[2]. Practice
[3] Stats  
[4] Reset
[5] Exit
  
## Testing  
`./vendor/bin/sail artisan test`

### [IMPORTANT] 
1. After running tests your database was refreshed to empty. Please, run the seeders again
2. Unfortunately, after each choice, the QANDA APP exits command mode, you need to run the command `./vendor/bin/sail artisan qanda:interactive`, 
to interact again with it. 
