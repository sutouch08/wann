<style>
  .check-title {
    background-color: #f8f8f8;
    border: solid 1px #CCC;
    padding:8px;
    font-size: 14px;
    text-align: center;
  }

  .check-table {
    padding-left: 0;
    padding-right: 0;
    min-height:200px;
    max-height: 800px;
    overflow: auto;
  }

  #request-table tr:nth-child(2) {
    background-color:#cddfca;
  }

  .view-port {
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    align-items:center;
  }

  #sticker {
    display: flex;
    border:solid 1px #ddd;
    width: 105mm;
    min-width: 50mm;
    height:32mm;
    min-height: 10mm;
    padding-left: 2mm;
    padding-right: 2mm;
    padding-top: 1mm;
    padding-bottom: 1mm
  }

  .sticker-label {
    border:solid 1px #ccc;
    min-width:20mm;
    min-height: 10mm;
    width:50%;
    border-radius: 5px;
    padding:2mm;
  }

  .label-space {
    width:0;
    height:100%;
  }

  .sticker-content {
    width: 100%;
    height:100%;
    border:1px;
    border-style: dashed;
    border-color:rgba(3,169,244,0.5);
    font-size:8px;
    font-weight: bold;
  }

</style>
