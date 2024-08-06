use phper::{
    modules::Module,
    values::ZVal,
    php_get_module,
    classes::{ClassEntity, Visibility},
};
use mysql_async::{prelude::*, Pool};
use std::sync::Mutex;
use std::thread;
use std::time::Duration;
use phper::objects::StateObj;
use std::collections::HashMap;
lazy_static::lazy_static! {
    static ref QUERY_RESULTS: Mutex<HashMap<String,String>> = Mutex::new(HashMap::new());
    static ref RUNTIME: tokio::runtime::Runtime = tokio::runtime::Runtime::new().unwrap();
}

struct AsyncDatabase;

impl AsyncDatabase {
    fn initialize(key: &str) -> phper::Result<String> {
        let mut query_results = QUERY_RESULTS.lock().unwrap();
        query_results.insert(key.to_string(), String::new());
        Ok((key).to_string())
        
    }

    fn async_db(query_index: &str, query: &str) -> Result<String, mysql_async::Error> {
        
        let query_string = query.to_string();
        let query_key = query_index.to_string();

        RUNTIME.spawn(async move {
            if let Err(e) = AsyncDatabase::async_db_task(&query_key, &query_string).await {
                eprintln!("Error in async_db_task: {:?}", e);
            }
        });
        
        
        Ok("Task started".to_string())
    }

    async fn async_db_task(query_index: &str, query: &str) -> Result<String, mysql_async::Error> {
        
        thread::sleep(Duration::from_secs(3));
        
        let db_url = "mysql://root:Jotformer.01@localhost:3306/rust_db";
        let pool = Pool::new(db_url);
        let mut conn = pool.get_conn().await?;
        let explain_query = format!("EXPLAIN {}", query);
        let mock_result = conn.query_drop(explain_query).await;
        match mock_result{
            Ok(_) => {
                let stmt = conn.prep(query).await?;
                let mut result = conn.exec_iter(stmt, ()).await?;

                let mut output = String::new();
        
                while let Ok(Some(row_result)) = result.next().await {
                    let row = row_result;
                    let id: i32 = row.get("id").unwrap_or(-1);
                    let username: String = row.get("username").unwrap_or(String::new());
                    let phone: String = row.get("phone").unwrap_or(String::new());
        
                    output.push_str(&format!("ID: {}, Username: {}, Phone: {}\n", id, username, phone));
        
                    let mut query_results = QUERY_RESULTS.lock().unwrap();
                    let query_index2 = query_index.to_string();
                    query_results.insert(query_index2, output.clone());
                }
                output.push_str("\0");
                let mut query_results = QUERY_RESULTS.lock().unwrap();
                let query_index2 = query_index.to_string();
                query_results.insert(query_index2, output.clone());
                
                
                Ok(output)
            }

            Err(_) => {
                
                let mut query_results = QUERY_RESULTS.lock().unwrap();
                let query_index2 = query_index.to_string();
                query_results.insert(query_index2, "invalid query\0".to_string());
                Ok("".to_string())
            }
        }
        
    }

    fn async_db_wrapper(args: &mut [ZVal]) -> phper::Result<String> {
        
        let query_index = args[0].expect_z_str()?.to_str()?;
        let query = args[1].expect_z_str()?.to_str()?;
        let output = AsyncDatabase::async_db(query_index, query);
        Ok(output.unwrap())
    }

    fn get_query_result(query_index: &str) -> phper::Result<String> {
        let query_results = QUERY_RESULTS.lock().unwrap();
        let result = query_results.get(query_index).unwrap_or(&String::new()).clone();
        Ok(result)
    }
}

#[php_get_module]
pub fn get_module() -> Module {
    let mut module = Module::new("dbasync", "0.1.0", "Furkan Karaca");
    const EXCEPTION_CLASS_NAME: &str = "AsyncDatabase";
    let mut class = ClassEntity::new(EXCEPTION_CLASS_NAME);

    class.add_method("async_db_wrapper", Visibility::Public, |_: &mut StateObj<()>, args: &mut [ZVal]| {
        
        let _ =AsyncDatabase::async_db_wrapper(args);
        Ok::<_, phper::Error>("")
    });

    class.add_method("get_query_result", Visibility::Public, |_: &mut StateObj<()>, args: &mut [ZVal]| {
        let query_key = args[0].expect_z_str()?.to_str()?;
        let result = AsyncDatabase::get_query_result(query_key)?;
        Ok::<_, phper::Error>(result)
    });

    class.add_method("initialize", Visibility::Public, |_this: &mut StateObj<()>, args: &mut [ZVal]| {
        let array_key = args[0].expect_z_str()?.to_str()?;
        let result = AsyncDatabase::initialize(array_key)?;
        Ok::<_, phper::Error>(result)
    });

    module.add_class(class);
    module
}
