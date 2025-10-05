import * as FileSaver from 'file-saver';

export class Utils {

  public static config(): any {
    return {
      baseUrl: "https://www.sito.it/api/",
      baseUrlFile: "https://www.sito.it/ALLEGATI/",
    }
  }

  public static exportExcel(rows: any[], name: string) {
    import('xlsx').then((xlsx) => {
      const worksheet = xlsx.utils.json_to_sheet(rows);
      const workbook = {Sheets: {data: worksheet}, SheetNames: ['data']};
      const excelBuffer: any = xlsx.write(workbook, {bookType: 'xlsx', type: 'array'});
      this.saveAsExcelFile(excelBuffer, name);
    });
  }

  private static saveAsExcelFile(buffer: any, fileName: string): void {
    const EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
    const EXCEL_EXTENSION = '.xlsx';
    const data: Blob = new Blob([buffer], {
      type: EXCEL_TYPE
    });
    FileSaver.saveAs(data, fileName + '_' + new Date().getTime() + EXCEL_EXTENSION);
  }

  public static formatDateMask(strDate: string): string {
    const splitDate = strDate.split('-');
    return splitDate[2] + '/' + splitDate[1] + '/' + splitDate[0];
  }

  public static formatDate(strDate: string): string {
    const splitDate = strDate.split('-');
    return splitDate[2] + '/' + splitDate[1] + '/' + splitDate[0];
  }

  public static floatToCurrency(value: number, locale = 'it-IT', currency = 'EUR', minDigits = 2): string {
    return value.toLocaleString(locale, {
      style: 'currency',
      currency: currency,
      minimumFractionDigits: minDigits,
    });
  }

}
