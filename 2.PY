import mysql.connector
import yagmail
import time
from apscheduler.schedulers.background import BackgroundScheduler 


def send_email_at_time(to_email, subject, body, schedule_date, id, from_email):
    yag.send(from_email, subject, body)
    scheduled_time = schedule_date
    
    conn = mysql.connector.connect(user='admin', password='adminadmin', host='future-db.csc2uqqp87hs.ap-south-1.rds.amazonaws.com', database='email_db')
    cursor = conn.cursor()
    query = "UPDATE emails SET status = 'SENT' WHERE id = %s"
    cursor.execute(query, (id,))
    conn.commit()
    cursor.close()
    conn.close()

    print(f"Email sent successfully for SL No: {id} Time: {scheduled_time}")
    yag.send(to_email, "Confirmation of email delivery", f"Your email scheduled for {scheduled_time} has been successfully sent to {to_email}")

def send_email():
    global yag
    # Connect to the database
    conn = mysql.connector.connect(user='root', password='', host='localhost', database='email_db')
    cursor = conn.cursor()
    
    # Fetch pending emails from the database
    query = "SELECT id, to_email, body, schedule_date, name, from_email FROM emails WHERE status = 'VERIFIED'"
    cursor.execute(query)
    emails = cursor.fetchall()
    
    # If there are pending emails, send them
    if emails:
        yag = yagmail.SMTP('as7204447210@gmail.com', 'fcdoulhjgbcpdrpj')
        scheduler = BackgroundScheduler()
        scheduler.start()
        for email in emails:
            id, to_email, body, schedule_date, name, from_email = email
            
            # Schedule the email to be sent at the specified time
            scheduler.add_job(send_email_at_time, 'date', run_date=schedule_date, args=[to_email, "Message from the future me", body, schedule_date, id, from_email])
            
            # Update the email status in the database
            query = "UPDATE emails SET status = 'SCHEDULED' WHERE id = %s"
            cursor.execute(query, (id,))
            conn.commit()
            
            # Wait for a few seconds to allow the email to be sent
            time.sleep(5)
            
            # Verify that the email has been sent successfully
            print(f"Scheduling email for SL No: {id} Time: {schedule_date}")
        print("All emails scheduled")
    else:
        print("No pending messages")
    
    # Close the database connection
    cursor.close()
    conn.close()

while True:
    send_email()
    time.sleep(10)
